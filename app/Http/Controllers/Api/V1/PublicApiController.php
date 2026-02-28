<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ShowcaseProject;
use App\Models\BlogPost;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\PageContent;
use App\Services\SeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicApiController extends Controller
{
    public function __construct(
        private SeoService $seoService
    ) {}
    public function landing(Request $request): JsonResponse
    {
        $locale = $request->query('locale', 'en');
        
        $services = Service::where('is_active', true)->orderBy('order')->take(3)->get();
        $projects = ShowcaseProject::where('is_active', true)->orderBy('created_at', 'desc')->take(6)->get();
        $seo = SeoSetting::where('page_slug', 'home')->first();
        
        // Stats could be computed or static right now
        $stats = [
            'projects_completed' => 120,
            'happy_clients' => 85,
            'years_experience' => 5,
            'team_members' => 15,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'hero' => [
                    'title_ar' => 'رؤيتك الرقمية، واقع ملموس',
                    'title_en' => 'Your Digital Vision, Realized',
                    'subtitle_ar' => 'نقدم حلولاً برمجية مبتكرة تدعم نمو أعمالك وتحقق تطلعاتك',
                    'subtitle_en' => 'We deliver innovative software solutions powering your business growth.',
                    'cta_text_ar' => 'ابدأ مشروعك معنا',
                    'cta_text_en' => 'Start Your Project',
                ],
                'services' => $services,
                'projects' => $projects,
                'stats' => $stats,
                'seo' => $seo ?: [
                    'title_en' => 'Home', 'title_ar' => 'الرئيسية',
                    'description_en' => 'Welcome to our agency.', 'description_ar' => 'أهلاً بك في وكالتنا.',
                    'keywords_en' => 'agency, software', 'keywords_ar' => 'وكالة, برمجة'
                ]
            ]
        ]);
    }

    public function services(Request $request): JsonResponse
    {
        $services = Service::where('is_active', true)->orderBy('order')->get();
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function projects(Request $request): JsonResponse
    {
        $projects = ShowcaseProject::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function project($id, Request $request): JsonResponse
    {
        $project = ShowcaseProject::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function blog(Request $request): JsonResponse
    {
        // Simple pagination
        $posts = BlogPost::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['author', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(9);
            
        return response()->json($posts);
    }

    public function blogPost($slug, Request $request): JsonResponse
    {
        $locale = $request->query('locale', 'en');
        $field = $locale === 'ar' ? 'slug_ar' : 'slug_en';

        $post = BlogPost::where($field, $slug)
            ->where('status', 'published')
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        // Increment views
        $post->increment('views');

        // Generate SEO data
        $jsonLd = $this->seoService->generateBlogPostJsonLd($post, $locale);
        $ogTags = $this->seoService->generateBlogPostOgTags($post, $locale);

        return response()->json([
            'success' => true,
            'data' => [
                'post' => $post,
                'seo' => [
                    'json_ld' => $jsonLd,
                    'og_tags' => $ogTags,
                ],
            ],
        ]);
    }

    public function relatedPosts($postId, Request $request): JsonResponse
    {
        $post = BlogPost::findOrFail($postId);
        // Find by category logic (simplified to taking random 3 or recent 3)
        $related = BlogPost::where('id', '!=', $postId)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $related
        ]);
    }

    public function seo($pageSlug, Request $request): JsonResponse
    {
        $seo = SeoSetting::where('page_slug', $pageSlug)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => $seo
        ]);
    }

    public function settings(Request $request): JsonResponse
    {
         $settings = SiteSetting::all()->mapWithKeys(function ($setting) {
             return [$setting->key => $setting]; 
         });
         return response()->json([
             'success' => true,
             'data' => $settings
         ]);
    }

    public function socialLinks(): JsonResponse
    {
        $links = SocialLink::where('is_active', true)->orderBy('order')->get();
        return response()->json([
            'success' => true,
            'data' => $links
        ]);
    }

    public function pageContent($pageSlug, Request $request): JsonResponse
    {
        $content = PageContent::where('page_slug', $pageSlug)
            ->get()
            ->keyBy('section_key');
            
        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }
    
    public function about(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                 'mission_ar' => 'مهمتنا بناء المستقبل.',
                 'mission_en' => 'Our mission is to build the future.',
                 'vision_ar' => 'رؤيتنا الريادة.',
                 'vision_en' => 'Our vision is to lead.',
            ]
        ]);
    }
}
