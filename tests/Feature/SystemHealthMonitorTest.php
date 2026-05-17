<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemHealthMonitorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed initial setting record
        $this->seed(\Database\Seeders\SettingSeeder::class);

        // Update with custom properties for testing
        Setting::first()->update([
            'seller_password' => bcrypt('devpass123'),
            'gemini_tokens_this_month' => 500,
            'groq_tokens_this_month' => 1200,
            'wa_api_url' => 'https://api.fonnte.com/send',
            'wa_api_token' => 'fake-wa-token'
        ]);
    }

    /** @test */
    public function unauthenticated_seller_is_redirected_to_login()
    {
        $response = $this->get(route('seller.dashboard'));
        $response->assertRedirect(route('seller.login'));
    }

    /** @test */
    public function authenticated_seller_can_access_dashboard_with_health_metrics()
    {
        // Authenticate seller session
        $response = $this->withSession(['seller_logged_in' => true])
            ->get(route('seller.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('systemHealth');
        $response->assertSee('Pemantauan Kesehatan Sistem');
    }

    /** @test */
    public function downloading_non_existent_backup_returns_404_error()
    {
        $response = $this->withSession(['seller_logged_in' => true])
            ->get(route('seller.download_backup', ['filename' => 'nonexistent-backup-file.zip']));

        $response->assertStatus(404);
    }

    /** @test */
    public function deleting_non_existent_backup_returns_404_error()
    {
        $response = $this->withSession(['seller_logged_in' => true])
            ->delete(route('seller.delete_backup', ['filename' => 'nonexistent-backup-file.zip']));

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'File backup tidak ditemukan.'
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_download_or_delete_backups()
    {
        // Try download without session
        $downloadResponse = $this->get(route('seller.download_backup', ['filename' => 'backup.zip']));
        $downloadResponse->assertStatus(403);

        // Try delete without session
        $deleteResponse = $this->delete(route('seller.delete_backup', ['filename' => 'backup.zip']));
        $deleteResponse->assertStatus(403);
    }
}
