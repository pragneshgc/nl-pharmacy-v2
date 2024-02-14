<?php

namespace Tests\Feature;

use App\Mail\SafeIPMail;
use App\Models\Safeip;
use App\Models\Setting;
use Database\Seeders\SettingTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckIPTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_redirect_to_login_page_if_ip_not_valid(): void
    {
        $response = $this->get("/");

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_redirect_to_restricted_page_if_ip_not_valid(): void
    {
        $fakeIP = fake()->ipv4();
        $this->withServerVariables(['REMOTE_ADDR' => $fakeIP]);

        $response = $this->get("/login");
        $response->assertStatus(302);
        $response->assertRedirect('/restricted');
    }

    public function test_redirect_to_restricted_page_if_ip_not_approved(): void
    {
        $fakeIP = fake()->ipv4();
        Safeip::factory()->create([
            'SafeIP' => $fakeIP
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => $fakeIP]);
        $response = $this->get("/login");

        $this->assertDatabaseHas('SafeIP', ['SafeIP' => $fakeIP, 'Status' => 0]);
        $response->assertStatus(302);
        $response->assertRedirect('/restricted');
    }

    public function test_show_login_page_if_ip_is_approved(): void
    {
        $this->withMiddleware(['checkip']);
        $fakeIP = fake()->ipv4();
        Safeip::factory()->create([
            'SafeIP' => $fakeIP,
            'Status' => 1
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => $fakeIP]);
        $response = $this->get("/login");

        $this->assertDatabaseHas('SafeIP', ['SafeIP' => $fakeIP, 'Status' => 1]);
        $response->assertStatus(200);
    }

    public function test_redirect_to_login_from_rescricted_if_ip_is_approved(): void
    {
        $this->withMiddleware(['checkip']);
        $fakeIP = fake()->ipv4();
        Safeip::factory()->create([
            'SafeIP' => $fakeIP,
            'Status' => 1
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => $fakeIP]);
        $response = $this->get("/restricted");

        $this->assertDatabaseHas('SafeIP', ['SafeIP' => $fakeIP, 'Status' => 1]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_request_an_ip_for_approval()
    {
        $this->seed(SettingTableSeeder::class);
        $fakeIP = fake()->ipv4();
        $this->withServerVariables(['REMOTE_ADDR' => $fakeIP]);

        Mail::fake();

        $response = $this->post("/restricted-ip-mail", ['name' => 'some name']);
        $response->assertStatus(302);
        $response->assertRedirect('/restricted');
        $this->assertDatabaseHas('SafeIP', ['SafeIP' => $fakeIP, 'Status' => 0]);

        $list = Setting::where('Type', 902)->value('Value');
        $list = explode(',', $list);
        $list = array_map('trim', $list);

        Mail::assertSent(SafeIPMail::class, function ($mail) use ($list) {
            $this->assertEqualsCanonicalizing($list, array_column($mail->to, 'address'));
            $this->assertEquals('Safe IP Email', $mail->subject);
            return true;
        });
    }

    public function test_approve_safe_ip_show_error_if_key_not_exist()
    {
        $response = $this->get('/add-safe-ip');
        $response->assertContent('Invalid request');
    }

    public function test_approve_safe_ip_show_error_if_ip_already_whitelisted()
    {
        $fakeIP = fake()->ipv4();
        Safeip::factory()->create([
            'SafeIP' => $fakeIP,
            'SafeKey' => 'SomeOtherKey'
        ]);
        $response = $this->get('/add-safe-ip?key=using-other-key');
        $response->assertContent('Cannot add IP to whitelist (invalid token or IP already whitelisted)');
    }

    public function test_approve_safe_ip_success()
    {
        $fakeIP = fake()->ipv4();
        Safeip::factory()->create([
            'SafeIP' => $fakeIP,
            'SafeKey' => 'using-same-key'
        ]);
        $response = $this->get('/add-safe-ip?key=using-same-key');
        $response->assertContent('IP added to whitelist');
        $this->assertDatabaseHas('SafeIP', ['SafeIP' => $fakeIP, 'Status' => 1]);
    }
}
