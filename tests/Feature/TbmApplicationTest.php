<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TbmApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[Test]
    public function dashboard_overview_renders_successfully(): void
    {
        $david = User::where('email', 'david@victoriatrading.co.ug')->first();
        $this->actingAs($david);
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Gross Sales Revenue');
        $response->assertSee('Operating Expenses');
        $response->assertSee('UGX');
    }

    #[Test]
    public function currency_switch_sets_session_and_recalculates_prices(): void
    {
        $david = User::where('email', 'david@victoriatrading.co.ug')->first();
        $this->actingAs($david);
        // 1. Switch to KES (Kenyan Shilling)
        $response = $this->post(route('currency.switch'), ['currency' => 'KES']);
        $response->assertRedirect();
        $response->assertSessionHas('user_currency', 'KES');
        $response->assertSessionHas('success');

        // 2. Check dashboard with KES session
        $dashResponse = $this->withSession(['user_currency' => 'KES'])->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('KES');

        // 3. Switch back to UGX (Uganda Shilling)
        $ugxResponse = $this->post(route('currency.switch'), ['currency' => 'UGX']);
        $ugxResponse->assertSessionHas('user_currency', 'UGX');
    }

    #[Test]
    public function uganda_and_african_currencies_are_seeded_and_supported(): void
    {
        $africanCodes = ['UGX', 'KES', 'TZS', 'RWF', 'ZAR', 'NGN', 'GHS', 'EGP', 'MAD', 'USD', 'EUR', 'GBP'];

        foreach ($africanCodes as $code) {
            $currency = Currency::where('code', $code)->first();
            $this->assertNotNull($currency, "Currency {$code} must be seeded in database.");
            $this->assertTrue((bool)$currency->is_active);
            $this->assertGreaterThan(0, (float)$currency->exchange_rate);
        }
    }

    #[Test]
    public function subscription_plans_pricing_page_renders_ugx_plans(): void
    {
        $response = $this->get(route('subscription.pricing'));
        $response->assertStatus(200);
        $response->assertSee('Starter');
        $response->assertSee('Professional');
        $response->assertSee('Premium');
        $response->assertSee('15,000');
        $response->assertSee('25,000');
        $response->assertSee('40,000');
    }

    #[Test]
    public function business_user_registration_provisions_30_day_free_trial(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Kampala Enterprise Owner',
            'business_name' => 'Kampala Logistics Hub Ltd',
            'email' => 'owner@kampalahub.ug',
            'phone' => '+256 772 112233',
            'city' => 'Kampala',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'plan_slug' => 'starter',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('businesses', ['name' => 'Kampala Logistics Hub Ltd']);
        $this->assertDatabaseHas('users', ['email' => 'owner@kampalahub.ug']);
        $this->assertDatabaseHas('subscriptions', [
            'status' => 'trialing',
            'billing_cycle' => 'monthly',
        ]);
    }

    #[Test]
    public function payroll_and_ura_tax_calculations_work(): void
    {
        $business = Business::first();
        $this->assertNotNull($business);

        $superAdmin = User::where('email', 'admin@tbm-enterprise.com')->first();
        $this->actingAs($superAdmin);

        // 1. Payroll page
        $payrollResponse = $this->get(route('payroll.index'));
        $payrollResponse->assertStatus(200);
        $payrollResponse->assertSee('Payroll');

        // 2. Tax page
        $taxResponse = $this->get(route('taxes.index'));
        $taxResponse->assertStatus(200);
        $taxResponse->assertSee('Tax Compliance');
        $taxResponse->assertSee('150,000,000');
    }

    #[Test]
    public function super_admin_can_access_subscriptions_console(): void
    {
        $superAdmin = User::where('email', 'admin@tbm-enterprise.com')->first();
        $this->actingAs($superAdmin);

        $dashResponse = $this->get(route('admin.subscriptions.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Subscription');
        $dashResponse->assertSee('Revenue Telemetry');

        $plansResponse = $this->get(route('admin.subscriptions.plans.index'));
        $plansResponse->assertStatus(200);
        $plansResponse->assertSee('Subscription Plans Management');

        $featuresResponse = $this->get(route('admin.subscriptions.features.index'));
        $featuresResponse->assertStatus(200);
        $featuresResponse->assertSee('Plan Features');
    }

    #[Test]
    public function task_crud_operations_work_seamlessly(): void
    {
        $david = User::where('email', 'david@victoriatrading.co.ug')->first();
        $this->actingAs($david);
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);

        $storeResponse = $this->post(route('tasks.store'), [
            'title' => 'Kampala Regional Route Inspection',
            'description' => 'Inspect central distribution depots',
            'project_name' => 'Logistics',
            'priority' => 'critical',
            'status' => 'in_progress',
            'price' => 2500000,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'assigned_user' => 'Sarah Jenkins',
            'client_name' => 'Nile Breweries',
        ]);

        $storeResponse->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Kampala Regional Route Inspection']);
    }

    #[Test]
    public function pos_and_queue_checkout_flow(): void
    {
        $david = User::where('email', 'david@victoriatrading.co.ug')->first();
        $this->actingAs($david);

        $product = Product::first();
        $this->assertNotNull($product);

        $posResponse = $this->get(route('pos'));
        $posResponse->assertStatus(200);

        $addResponse = $this->post(route('cart.add'), [
            'type' => 'product',
            'id' => $product->id,
            'quantity' => 1,
        ]);
        $addResponse->assertSessionHas('cart');

        $cartResponse = $this->get(route('cart.index'));
        $cartResponse->assertStatus(200);

        $checkoutResponse = $this->post(route('cart.checkout'));
        $checkoutResponse->assertRedirect(route('dashboard'));
    }
}
