<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Http\Requests\IndexBlogPostRequest;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use App\Services\BlogPostService;
use Illuminate\Http\JsonResponse;

class BlogPostController extends Controller
{
    public function __construct(
        private BlogPostService $blogPostService
    ) {}

    public function index(IndexBlogPostRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $posts = $this->blogPostService->getPosts($filters);

        return response()->json([
            'success' => true,
            'data' => BlogPostResource::collection($posts),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'from' => $posts->firstItem(),
                'to' => $posts->lastItem(),
            ],
        ]);
    }

    public function store(StoreBlogPostRequest $request): JsonResponse
    {
        try {
            $post = $this->blogPostService->createPost($request->validated());

            return response()->json([
                'success' => true,
                'data' => new BlogPostResource($post),
                'message' => __('blog.created'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create blog post: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(BlogPost $blogPost): JsonResponse
    {
        $blogPost->load(['category', 'author', 'tags']);

        return response()->json([
            'success' => true,
            'data' => new BlogPostResource($blogPost),
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): JsonResponse
    {
        try {
            $updatedPost = $this->blogPostService->updatePost($blogPost, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new BlogPostResource($updatedPost),
                'message' => __('blog.updated'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update blog post: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(BlogPost $blogPost): JsonResponse
    {
        try {
            $this->blogPostService->deletePost($blogPost);

            return response()->json([
                'success' => true,
                'message' => __('blog.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete blog post: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function restore(BlogPost $blogPost): JsonResponse
    {
        try {
            $blogPost->restore();

            return response()->json([
                'success' => true,
                'data' => new BlogPostResource($blogPost->fresh(['category', 'author', 'tags'])),
                'message' => __('blog.restored'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore blog post: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function publish(BlogPost $blogPost): JsonResponse
    {
        try {
            $this->blogPostService->publishPost($blogPost);

            return response()->json([
                'success' => true,
                'data' => new BlogPostResource($blogPost->fresh()),
                'message' => __('blog.published'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish blog post: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function schedule(BlogPost $blogPost, UpdateBlogPostRequest $request): JsonResponse
    {
        try {
            $this->blogPostService->schedulePost($blogPost, $request->validated());

            return response()->json([
                'success' => true,
                'data' => new BlogPostResource($blogPost->fresh()),
                'message' => __('blog.scheduled'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule blog post: ' . $e->getMessage(),
            ], 500);
        }
    }
}
