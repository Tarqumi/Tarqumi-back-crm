<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ShowcaseProject;
use Illuminate\Http\JsonResponse;

class LandingPageController extends Controller
{
    /**
     * Get showcase projects for landing page
     */
    public function showcaseProjects(): JsonResponse
    {
        $projects = ShowcaseProject::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    /**
     * Get company information
     */
    public function companyInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'name' => 'Tarqumi',
                'tagline_ar' => 'نبني مستقبلك الرقمي',
                'tagline_en' => 'Building Your Digital Future',
                'description_ar' => 'شركة تطوير برمجيات متخصصة في بناء المواقع والأنظمة وتطبيقات الجوال للشركات في جميع المجالات',
                'description_en' => 'A software development company specialized in building websites, systems, and mobile apps for businesses across all industries',
            ],
        ]);
    }
}
