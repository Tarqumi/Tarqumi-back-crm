<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Traits\ValidatesFileUploads;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Mews\Purifier\Facades\Purifier;

class BlogPostService
{
    use ValidatesFileUploads;
    public function __construct(
        private RevalidationService $revalidationService
    ) {}

    public function createPost(array $data): BlogPost
    {
        return DB::transaction(function () use ($data) {
            // Sanitize rich text content to prevent XSS
            if (isset($data['content_ar'])) {
                $data['content_ar'] = Purifier::clean($data['content_ar']);
            }
            if (isset($data['content_en'])) {
                $data['content_en'] = Purifier::clean($data['content_en']);
            }

            // Handle featured image upload
            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $data['featured_image'] = $this->uploadFeaturedImage($data['featured_image']);
            }

            // Extract tags
            $tags = $data['tags'] ?? [];
            unset($data['tags']);

            // Set author
            $data['author_id'] = Auth::id();

            // Create post
            $post = BlogPost::create($data);

            // Attach tags
            if (!empty($tags)) {
                $post->tags()->attach($tags);
            }

            // Trigger revalidation if published
            if ($post->status === 'published') {
                $this->revalidationService->revalidateBlogPost($post->slug_ar, $post->slug_en);
            }

            return $post->load(['category', 'author', 'tags']);
        });
    }

    public function updatePost(BlogPost $post, array $data): BlogPost
    {
        return DB::transaction(function () use ($post, $data) {
            // Sanitize rich text content to prevent XSS
            if (isset($data['content_ar'])) {
                $data['content_ar'] = Purifier::clean($data['content_ar']);
            }
            if (isset($data['content_en'])) {
                $data['content_en'] = Purifier::clean($data['content_en']);
            }

            // Handle featured image upload
            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                // Delete old image
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $data['featured_image'] = $this->uploadFeaturedImage($data['featured_image']);
            }

            // Extract tags if provided
            $tags = null;
            if (isset($data['tags'])) {
                $tags = $data['tags'];
                unset($data['tags']);
            }

            // Update post
            $post->update($data);

            // Sync tags if provided
            if ($tags !== null) {
                $post->tags()->sync($tags);
            }

            // Trigger revalidation if published
            if ($post->status === 'published') {
                $this->revalidationService->revalidateBlogPost($post->slug_ar, $post->slug_en);
            }

            return $post->fresh(['category', 'author', 'tags']);
        });
    }

    public function deletePost(BlogPost $post): bool
    {
        return $post->delete();
    }

    public function publishPost(BlogPost $post): BlogPost
    {
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $post->fresh();
    }

    public function schedulePost(BlogPost $post, array $data): BlogPost
    {
        $post->update([
            'status' => 'scheduled',
            'scheduled_at' => $data['scheduled_at'],
        ]);

        return $post->fresh();
    }

    public function getPosts(array $filters): LengthAwarePaginator
    {
        $query = BlogPost::query();

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('excerpt_ar', 'like', "%{$search}%")
                  ->orWhere('excerpt_en', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter by author
        if (!empty($filters['author_id'])) {
            $query->where('author_id', $filters['author_id']);
        }

        // Filter by featured
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', $filters['is_featured']);
        }

        // Filter by tag
        if (!empty($filters['tag_id'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('blog_tags.id', $filters['tag_id']);
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'published_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $filters['per_page'] ?? 15;
        
        return $query->with(['category', 'author', 'tags'])->paginate($perPage);
    }

    private function uploadFeaturedImage(UploadedFile $file): string
    {
        // Validate file using trait
        $this->validateImageUpload($file, 20);

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store in public disk
        $path = $file->storeAs('blog/featured', $filename, 'public');

        return $path;
    }

    private function validateImage(UploadedFile $file): void
    {
        // Deprecated: Use validateImageUpload from trait instead
        $this->validateImageUpload($file, 20);
    }
}
