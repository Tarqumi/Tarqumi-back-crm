<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ShowcaseProject;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function generateSitemap(): string
    {
        $baseUrl = config('app.url');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // Static pages (both languages)
        $staticPages = [
            ['slug' => '', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['slug' => 'about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['slug' => 'services', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['slug' => 'projects', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['slug' => 'blog', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['slug' => 'contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= $this->generateUrlEntry(
                "{$baseUrl}/ar/{$page['slug']}",
                "{$baseUrl}/en/{$page['slug']}",
                now()->toAtomString(),
                $page['changefreq'],
                $page['priority']
            );
        }

        // Blog posts (published only)
        $blogPosts = BlogPost::published()->get();
        foreach ($blogPosts as $post) {
            $xml .= $this->generateUrlEntry(
                "{$baseUrl}/ar/blog/{$post->slug_ar}",
                "{$baseUrl}/en/blog/{$post->slug_en}",
                $post->updated_at->toAtomString(),
                'weekly',
                '0.7'
            );
        }

        // Showcase projects (active only)
        $projects = ShowcaseProject::where('is_active', true)->get();
        foreach ($projects as $project) {
            $xml .= $this->generateUrlEntry(
                "{$baseUrl}/ar/projects/{$project->id}",
                "{$baseUrl}/en/projects/{$project->id}",
                $project->updated_at->toAtomString(),
                'monthly',
                '0.6'
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private function generateUrlEntry(
        string $urlAr,
        string $urlEn,
        string $lastmod,
        string $changefreq,
        string $priority
    ): string {
        return "
    <url>
        <loc>{$urlAr}</loc>
        <lastmod>{$lastmod}</lastmod>
        <changefreq>{$changefreq}</changefreq>
        <priority>{$priority}</priority>
        <xhtml:link rel=\"alternate\" hreflang=\"ar\" href=\"{$urlAr}\" />
        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$urlEn}\" />
        <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"{$urlEn}\" />
    </url>";
    }
}
