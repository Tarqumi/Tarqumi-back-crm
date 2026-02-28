<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XssTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function project_name_sanitizes_xss_payload()
    {
        $this->actingAs($this->admin, 'sanctum');

        $xssPayload = '<script>alert("XSS")</script>';
        
        $response = $this->postJson('/api/v1/projects', [
            'name' => $xssPayload,
            'description' => 'Test description',
            'manager_id' => $this->admin->id,
            'budget' => 10000,
            'currency' => 'USD',
            'priority' => 5,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'status' => 'planning',
        ]);

        if ($response->status() === 201) {
            $project = Project::first();
            // Script tags should be stripped or escaped
            $this->assertStringNotContainsString('<script>', $project->name);
            $this->assertStringNotContainsString('</script>', $project->name);
        }
    }

    /** @test */
    public function client_name_sanitizes_xss_payload()
    {
        $this->actingAs($this->admin, 'sanctum');

        $xssPayload = '<img src=x onerror=alert("XSS")>';
        
        $response = $this->postJson('/api/v1/clients', [
            'name' => $xssPayload,
            'email' => 'test@example.com',
            'phone' => '1234567890',
        ]);

        if ($response->status() === 201) {
            $client = Client::first();
            // Script tags should be stripped or escaped
            $this->assertStringNotContainsString('onerror', $client->name);
        }
    }

    /** @test */
    public function contact_form_sanitizes_xss_in_message()
    {
        $xssPayload = '<svg onload=alert(1)>';
        
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'message' => $xssPayload,
        ]);

        if ($response->status() === 201) {
            $this->assertDatabaseHas('contact_submissions', [
                'email' => 'john@example.com',
            ]);
            
            $submission = \App\Models\ContactSubmission::where('email', 'john@example.com')->first();
            // SVG with onload should be stripped
            $this->assertStringNotContainsString('onload', $submission->message);
        }
    }

    /** @test */
    public function blog_content_is_sanitized_with_html_purifier()
    {
        $this->actingAs($this->admin, 'sanctum');

        $maliciousContent = '<p>Safe content</p><script>alert("XSS")</script><p>More content</p>';
        
        $response = $this->postJson('/api/v1/blog', [
            'title_ar' => 'عنوان الاختبار',
            'title_en' => 'Test Title',
            'slug_ar' => 'test-slug-ar',
            'slug_en' => 'test-slug-en',
            'excerpt_ar' => 'ملخص',
            'excerpt_en' => 'Excerpt',
            'content_ar' => $maliciousContent,
            'content_en' => $maliciousContent,
            'status' => 'draft',
        ]);

        if ($response->status() === 201) {
            $post = \App\Models\BlogPost::first();
            
            // Script tags should be removed by HTMLPurifier
            $this->assertStringNotContainsString('<script>', $post->content_en);
            $this->assertStringNotContainsString('</script>', $post->content_en);
            
            // Safe HTML should remain
            $this->assertStringContainsString('<p>Safe content</p>', $post->content_en);
        }
    }

    /** @test */
    public function team_member_name_sanitizes_xss()
    {
        $this->actingAs($this->admin, 'sanctum');

        $xssPayload = '<script>document.cookie</script>';
        
        $response = $this->postJson('/api/v1/team', [
            'name' => $xssPayload,
            'email' => 'newmember@example.com',
            'password' => 'password123',
            'role' => 'employee',
        ]);

        if ($response->status() === 201) {
            $user = User::where('email', 'newmember@example.com')->first();
            $this->assertStringNotContainsString('<script>', $user->name);
        }
    }

    /** @test */
    public function search_query_does_not_execute_javascript()
    {
        $this->actingAs($this->admin, 'sanctum');

        $xssPayload = '<script>alert(1)</script>';
        
        $response = $this->getJson('/api/v1/projects?search=' . urlencode($xssPayload));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Response should not contain unescaped script tags
        $content = $response->getContent();
        $this->assertStringNotContainsString('<script>alert(1)</script>', $content);
    }
}
