<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Support\Facades\URL;

class SeoService
{
    /**
     * Generate JSON-LD structured data for a blog post
     *
     * @param BlogPost $post
     * @param string $locale
     * @return array
     */
    public function generateBlogPostJsonLd(BlogPost $post, string $locale = 'en'): array
    {
        $isArabic = $locale === 'ar';
        
        // Get localized content
        $title = $isArabic ? $post->title_ar : $post->title_en;
        $excerpt = $isArabic ? $post->excerpt_ar : $post->excerpt_en;
        $slug = $isArabic ? $post->slug_ar : $post->slug_en;
        
        // Build the URL
        $url = config('app.frontend_url') . "/{$locale}/blog/{$slug}";
        
        // Build image URL
        $imageUrl = $post->featured_image 
            ? URL::to('storage/' . $post->featured_image)
            : config('app.frontend_url') . '/default-blog-image.jpg';

        // Base Article schema
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $excerpt,
            'image' => $imageUrl,
            'datePublished' => $post->published_at?->toIso8601String() ?? $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Tarqumi',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => config('app.frontend_url') . '/logo.png',
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ],
        ];

        // Add article section if category exists
        if ($post->category) {
            $jsonLd['articleSection'] = $isArabic ? $post->category->name_ar : $post->category->name_en;
        }

        // Add keywords if available
        $keywords = $isArabic ? $post->seo_keywords_ar : $post->seo_keywords_en;
        if ($keywords) {
            $jsonLd['keywords'] = $keywords;
        }

        // Add word count
        $content = $isArabic ? $post->content_ar : $post->content_en;
        if ($content) {
            $wordCount = str_word_count(strip_tags($content));
            $jsonLd['wordCount'] = $wordCount;
        }

        return $jsonLd;
    }

    /**
     * Generate JSON-LD for breadcrumbs
     *
     * @param array $items Array of ['name' => 'Name', 'url' => 'URL']
     * @return array
     */
    public function generateBreadcrumbJsonLd(array $items): array
    {
        $listItems = [];
        
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Generate JSON-LD for organization
     *
     * @return array
     */
    public function generateOrganizationJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Tarqumi',
            'url' => config('app.frontend_url'),
            'logo' => config('app.frontend_url') . '/logo.png',
            'description' => 'Software development company building websites, systems, and mobile apps',
            'sameAs' => [
                // These will be populated from social links in the database
            ],
        ];
    }

    /**
     * Generate Open Graph meta tags for blog post
     *
     * @param BlogPost $post
     * @param string $locale
     * @return array
     */
    public function generateBlogPostOgTags(BlogPost $post, string $locale = 'en'): array
    {
        $isArabic = $locale === 'ar';
        
        $title = $isArabic ? ($post->seo_title_ar ?? $post->title_ar) : ($post->seo_title_en ?? $post->title_en);
        $description = $isArabic ? ($post->seo_description_ar ?? $post->excerpt_ar) : ($post->seo_description_en ?? $post->excerpt_en);
        $slug = $isArabic ? $post->slug_ar : $post->slug_en;
        $url = config('app.frontend_url') . "/{$locale}/blog/{$slug}";
        
        $imageUrl = $post->featured_image 
            ? URL::to('storage/' . $post->featured_image)
            : config('app.frontend_url') . '/default-blog-image.jpg';

        return [
            'og:type' => 'article',
            'og:title' => $title,
            'og:description' => $description,
            'og:url' => $url,
            'og:image' => $imageUrl,
            'og:site_name' => 'Tarqumi',
            'article:published_time' => $post->published_at?->toIso8601String() ?? $post->created_at->toIso8601String(),
            'article:modified_time' => $post->updated_at->toIso8601String(),
            'article:author' => $post->author->name,
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $imageUrl,
        ];
    }
}
