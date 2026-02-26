<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogPostController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\LandingPageController;
use App\Http\Controllers\Api\V1\PageContentController;
use App\Http\Controllers\Api\V1\PasswordResetController;
use App\Http\Controllers\Api\V1\PermissionsController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\SeoSettingController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SiteSettingController;
use App\Http\Controllers\Api\V1\SitemapController;
use App\Http\Controllers\Api\V1\SocialLinkController;
use App\Http\Controllers\Api\V1\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are prefixed with /api and versioned as /api/v1/...
| Authentication uses Laravel Sanctum tokens.
|
*/

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1'); // Rate limit: 10 attempts per minute
    
    // Password Reset
    Route::post('/password/forgot', [PasswordResetController::class, 'forgot'])
        ->middleware('throttle:5,1'); // Rate limit: 5 attempts per minute
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])
        ->middleware('throttle:5,1'); // Rate limit: 5 attempts per minute
    
    // Landing Page
    Route::get('/landing/showcase-projects', [LandingPageController::class, 'showcaseProjects']);
    Route::get('/landing/company-info', [LandingPageController::class, 'companyInfo']);
    
    // Blog (Public - Published posts only)
    Route::get('/blog/posts', [BlogPostController::class, 'index']);
    Route::get('/blog/posts/{blogPost:slug_en}', [BlogPostController::class, 'show']);
    
    // Services (Public)
    Route::get('/services', [ServiceController::class, 'index']);
    
    // Social Links (Public)
    Route::get('/social-links', [SocialLinkController::class, 'index']);
    
    // Contact Form (Public with rate limiting)
    Route::post('/contact', [ContactController::class, 'submit'])
        ->middleware('throttle:5,1'); // 5 submissions per minute
});

// SEO routes (public, no authentication)
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /login\n";
    $content .= "Disallow: /admin\n";
    $content .= "Disallow: /dashboard\n";
    $content .= "Disallow: /api/v1/auth\n";
    $content .= "Disallow: /api/v1/team\n";
    $content .= "Disallow: /api/v1/clients\n";
    $content .= "Disallow: /api/v1/projects\n";
    $content .= "Disallow: /api/v1/cms\n";
    $content .= "Disallow: /api/v1/contact/submissions\n";
    $content .= "\n";
    $content .= "Sitemap: " . config('app.url') . "/sitemap.xml\n";
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware(['auth:sanctum', 'update.last.active'])->group(function () {
    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // Permissions
    Route::get('/permissions', [PermissionsController::class, 'index']);

    // Team Management (Admin, Super Admin, HR)
    Route::middleware('role:super_admin,admin,hr')->group(function () {
        Route::get('/team', [TeamController::class, 'index']);
        Route::post('/team', [TeamController::class, 'store']);
        Route::get('/team/departments', [TeamController::class, 'departments']);
        Route::get('/team/{user}', [TeamController::class, 'show']);
        Route::put('/team/{user}', [TeamController::class, 'update']);
        Route::patch('/team/{user}', [TeamController::class, 'update']);
        Route::delete('/team/{user}', [TeamController::class, 'destroy']);
        Route::post('/team/{oldManager}/reassign/{newManager}', [TeamController::class, 'reassign']);
    });

    // Client Management (Admin, Super Admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/clients', [ClientController::class, 'index']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::get('/clients/{client}', [ClientController::class, 'show']);
        Route::put('/clients/{client}', [ClientController::class, 'update']);
        Route::patch('/clients/{client}', [ClientController::class, 'update']);
        Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
        Route::post('/clients/{client}/restore', [ClientController::class, 'restore'])->withTrashed();
    });

    // Project Management (Admin, Super Admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::get('/projects/kanban', [ProjectController::class, 'kanban']);
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::patch('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
        Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->withTrashed();
    });
    
    // Landing Page CMS (Admin, Super Admin, CTO)
    Route::middleware('can.edit.landing')->group(function () {
        // Services Management
        Route::get('/cms/services', [ServiceController::class, 'index']);
        Route::post('/cms/services', [ServiceController::class, 'store']);
        Route::get('/cms/services/{service}', [ServiceController::class, 'show']);
        Route::put('/cms/services/{service}', [ServiceController::class, 'update']);
        Route::patch('/cms/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/cms/services/{service}', [ServiceController::class, 'destroy']);
        Route::post('/cms/services/reorder', [ServiceController::class, 'reorder']);
        
        // Blog Management
        Route::get('/cms/blog/posts', [BlogPostController::class, 'index']);
        Route::post('/cms/blog/posts', [BlogPostController::class, 'store']);
        Route::get('/cms/blog/posts/{blogPost}', [BlogPostController::class, 'show']);
        Route::put('/cms/blog/posts/{blogPost}', [BlogPostController::class, 'update']);
        Route::patch('/cms/blog/posts/{blogPost}', [BlogPostController::class, 'update']);
        Route::delete('/cms/blog/posts/{blogPost}', [BlogPostController::class, 'destroy']);
        Route::post('/cms/blog/posts/{blogPost}/restore', [BlogPostController::class, 'restore'])->withTrashed();
        Route::post('/cms/blog/posts/{blogPost}/publish', [BlogPostController::class, 'publish']);
        Route::post('/cms/blog/posts/{blogPost}/schedule', [BlogPostController::class, 'schedule']);
        
        // SEO Settings Management
        Route::get('/cms/seo', [SeoSettingController::class, 'index']);
        Route::get('/cms/seo/{pageSlug}', [SeoSettingController::class, 'show']);
        Route::put('/cms/seo/{pageSlug}', [SeoSettingController::class, 'update']);
        
        // Page Content Management
        Route::get('/cms/page-content', [PageContentController::class, 'index']);
        Route::post('/cms/page-content', [PageContentController::class, 'upsert']);
        Route::post('/cms/page-content/bulk', [PageContentController::class, 'bulkUpdate']);
        
        // Site Settings Management
        Route::get('/cms/site-settings', [SiteSettingController::class, 'index']);
        Route::get('/cms/site-settings/{key}', [SiteSettingController::class, 'show']);
        Route::post('/cms/site-settings', [SiteSettingController::class, 'upsert']);
        Route::post('/cms/site-settings/bulk', [SiteSettingController::class, 'bulkUpdate']);
        
        // Social Links Management
        Route::get('/cms/social-links', [SocialLinkController::class, 'adminIndex']);
        Route::post('/cms/social-links', [SocialLinkController::class, 'store']);
        Route::put('/cms/social-links/{socialLink}', [SocialLinkController::class, 'update']);
        Route::patch('/cms/social-links/{socialLink}', [SocialLinkController::class, 'update']);
        Route::delete('/cms/social-links/{socialLink}', [SocialLinkController::class, 'destroy']);
        Route::post('/cms/social-links/reorder', [SocialLinkController::class, 'reorder']);
    });
    
    // Contact Submissions Management (Admin, Super Admin, CTO)
    Route::middleware('can.view.contact')->group(function () {
        Route::get('/contact/submissions', [ContactController::class, 'index']);
        Route::get('/contact/submissions/statistics', [ContactController::class, 'statistics']);
        Route::get('/contact/submissions/{contactSubmission}', [ContactController::class, 'show']);
        Route::patch('/contact/submissions/{contactSubmission}/status', [ContactController::class, 'updateStatus']);
        Route::post('/contact/submissions/{contactSubmission}/spam', [ContactController::class, 'markAsSpam']);
        Route::delete('/contact/submissions/{contactSubmission}', [ContactController::class, 'destroy']);
    });
});
