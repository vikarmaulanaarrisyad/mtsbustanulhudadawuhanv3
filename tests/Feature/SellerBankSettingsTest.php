<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerBankSettingsTest extends TestCase
{
    /** @test */
    public function guests_cannot_update_bank_settings()
    {
        $response = $this->postJson(route('seller.update_bank_settings'), [
            'owner_bank_name' => 'BANK MANDIRI SYARIAH',
            'owner_bank_account' => '7889-0112-9988',
            'owner_bank_holder' => 'MARDIK DEV GROUP'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function authenticated_seller_can_update_bank_settings()
    {
        // Check if settings table has record, if not create one
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create([
                'owner_name' => 'Demo Owner',
                'email' => 'admin@mtsbustanulhuda.sch.id',
                'company_name' => 'Demo Company',
                'owner_bank_name' => 'BANK TRANSFER BCA',
                'owner_bank_account' => '8392-1209-9021',
                'owner_bank_holder' => 'PT MARDIK DIGITAL INDONESIA'
            ]);
        }

        $response = $this->withSession(['seller_logged_in' => true])
            ->postJson(route('seller.update_bank_settings'), [
                'owner_bank_name' => 'BANK MANDIRI SYARIAH',
                'owner_bank_account' => '7889-0112-9988',
                'owner_bank_holder' => 'MARDIK DEV GROUP'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Rekening & QRIS pembayaran berhasil disesuaikan!'
        ]);

        $this->assertEquals('BANK MANDIRI SYARIAH', Setting::first()->owner_bank_name);
        $this->assertEquals('7889-0112-9988', Setting::first()->owner_bank_account);
        $this->assertEquals('MARDIK DEV GROUP', Setting::first()->owner_bank_holder);

        // Reset to default
        Setting::first()->update([
            'owner_bank_name' => 'BANK TRANSFER BCA',
            'owner_bank_account' => '8392-1209-9021',
            'owner_bank_holder' => 'PT MARDIK DIGITAL INDONESIA'
        ]);
    }
}
