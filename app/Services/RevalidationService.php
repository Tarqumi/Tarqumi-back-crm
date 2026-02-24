<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RevalidationService
{
    /**
     * Revalidate a specific path in Next.js
     *
     * @param string $path The path to revalidate (e.g., '/en/about', '/ar/blog/post-slug')
     * @return bool
     */
    public function revalidatePath(string $path): bool
    {
        $revalidateUrl = config('services.nextjs.revalidate_url');
        $secret = config('services.nextjs.revalidation_secret');

        if (!$revalidateUrl || !$secret) {
            Log::warning('Next.js revalidation not configured', [
                'path' => $path,
                'url_configured' => !empty($revalidateUrl),
                'secret_configured' => !empty($secret),
            ]);
            return false;
        }

        try {
            $response = Http::timeout(5)->post($revalidateUrl, [
                'path' => $path,
                'secret' => $secret,
            ]);

            if ($response->successful()) {
                Log::info('Next.js path revalidated successfully', ['path' => $path]);
                return true;
            }

            Log::error('Next.js revalidation failed', [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Next.js revalidation exception', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Revalidate multiple paths
     *
     * @param array $paths Array of paths to revalidate
     * @return array ['success' => int, 'failed' => int]
     */
    public function revalidateMultiplePaths(array $paths): array
    {
        $success = 0;
        $failed = 0;

        foreach ($paths as $path) {
            if ($this->revalidatePath($path)) {
                $success++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
        ];
    }

    /**
     * Revalidate blog post in both languages
     *
     * @param string $slugAr Arabic slug
     * @param string $slugEn English slug
     * @return bool
     */
    public function revalidateBlogPost(string $slugAr, string $slugEn): bool
    {
        $paths = [
            '/ar/blog',
            '/en/blog',
            "/ar/blog/{$slugAr}",
            "/en/blog/{$slugEn}",
        ];

        $result = $this->revalidateMultiplePaths($paths);
        return $result['failed'] === 0;
    }

    /**
     * Revalidate all landing pages
     *
     * @return array
     */
    public function revalidateAllLandingPages(): array
    {
        $pages = ['', 'about', 'services', 'projects', 'blog', 'contact'];
        $paths = [];

        foreach ($pages as $page) {
            $paths[] = "/ar/{$page}";
            $paths[] = "/en/{$page}";
        }

        return $this->revalidateMultiplePaths($paths);
    }
}
