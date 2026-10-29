<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for the complete SaaS Subscription & Business Management Suite.
     */
    public function up(): void
    {
        // 1. Businesses / Organizations Table
        if (!Schema::hasTable('businesses')) {
            Schema::create('businesses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique()->nullable();
                $table->unsignedBigInteger('owner_id')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('tin_number')->nullable(); // Uganda TIN
                $table->string('address')->nullable();
                $table->string('city')->default('Kampala');
                $table->string('country')->default('Uganda');
                $table->string('currency_code', 10)->default('UGX');
                $table->string('currency_symbol', 10)->default('UGX');
                $table->string('logo_url')->nullable();
                $table->string('status')->default('active'); // active, suspended, trialing
                $table->timestamps();
            });
        }

        // 2. Business Locations Table
        if (!Schema::hasTable('business_locations')) {
            Schema::create('business_locations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('city')->default('Kampala');
                $table->string('phone')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }

        // 3. Subscription Plans Table
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Starter, Professional, Premium
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('badge')->nullable(); // e.g. "MOST POPULAR", "PREMIUM", "BEST VALUE"
                $table->bigInteger('monthly_price')->default(15000); // in UGX
                $table->bigInteger('annual_price')->default(150000); // in UGX
                $table->string('currency', 10)->default('UGX');
                $table->string('currency_symbol', 10)->default('UGX');
                $table->boolean('trial_enabled')->default(true);
                $table->integer('trial_duration')->default(30);
                $table->string('trial_unit')->default('Days');
                // Usage Limits (null means Unlimited)
                $table->integer('max_users')->nullable()->default(1);
                $table->integer('max_locations')->nullable()->default(1);
                $table->integer('max_products')->nullable()->default(100);
                $table->integer('max_invoices')->nullable()->default(100);
                $table->integer('max_receipts')->nullable()->default(100);
                $table->integer('max_purchases')->nullable()->default(100);
                $table->integer('max_vendors')->nullable()->default(50);
                $table->integer('max_customers')->nullable()->default(100);
                $table->integer('max_storage_mb')->nullable()->default(500);
                $table->integer('max_documents')->nullable()->default(200);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 4. Features Master Table
        if (!Schema::hasTable('features')) {
            Schema::create('features', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('key')->unique();
                $table->text('description')->nullable();
                $table->string('category')->default('General');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Subscription Plan Feature Mapping Table
        if (!Schema::hasTable('subscription_plan_features')) {
            Schema::create('subscription_plan_features', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
                $table->string('feature_key');
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();
            });
        }

        // 6. Subscriptions Table
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
                $table->string('billing_cycle')->default('monthly'); // monthly, yearly
                $table->bigInteger('amount')->default(15000); // in UGX
                $table->string('currency', 10)->default('UGX');
                $table->string('status')->default('trialing'); // trialing, active, past_due, expired, cancelled, suspended
                $table->timestamp('trial_starts_at')->nullable();
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamp('next_billing_date')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->string('payment_status')->default('pending'); // paid, pending, waived
                $table->timestamps();
            });
        }

        // 7. Subscription Payments Table
        if (!Schema::hasTable('subscription_payments')) {
            Schema::create('subscription_payments', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_reference')->unique();
                $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
                $table->bigInteger('amount'); // in UGX
                $table->string('currency', 10)->default('UGX');
                $table->string('gateway')->default('MTN Mobile Money'); // MTN MoMo, Airtel Money, Card, Manual Admin
                $table->string('status')->default('successful'); // successful, pending, failed
                $table->timestamp('paid_at')->nullable();
                $table->text('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 8. Subscription Invoices Table
        if (!Schema::hasTable('subscription_invoices')) {
            Schema::create('subscription_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
                $table->bigInteger('amount'); // in UGX
                $table->string('currency', 10)->default('UGX');
                $table->date('billing_period_start')->nullable();
                $table->date('billing_period_end')->nullable();
                $table->string('status')->default('paid'); // paid, pending, cancelled
                $table->string('payment_method')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // 9. Subscription Events & Audit Table
        if (!Schema::hasTable('subscription_events')) {
            Schema::create('subscription_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->cascadeOnDelete();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->cascadeOnDelete();
                $table->string('event_type'); // trial_started, renewed, upgraded, expired, extended
                $table->text('description');
                $table->text('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 10. Feature Usage Counter Table
        if (!Schema::hasTable('feature_usage')) {
            Schema::create('feature_usage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
                $table->string('feature_key');
                $table->integer('usage_count')->default(0);
                $table->timestamps();
            });
        }

        // 11. Subscription Settings Table
        if (!Schema::hasTable('subscription_settings')) {
            Schema::create('subscription_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string'); // string, integer, boolean, json
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // 12. Audit Logs Table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action'); // e.g. extend_trial, change_plan, update_settings
                $table->string('entity_type')->nullable();
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->text('description');
                $table->string('ip_address')->nullable();
                $table->text('metadata')->nullable();
                $table->timestamps();
            });
        }

        // 13. Expenses Table
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('category'); // Rent, Utilities, Salaries, Fuel, Supplies, Marketing, Taxes
                $table->bigInteger('amount_ugx'); // in UGX
                $table->date('expense_date');
                $table->string('vendor_name')->nullable();
                $table->string('payment_method')->default('Cash'); // Cash, Mobile Money, Bank Transfer, Card
                $table->string('reference_no')->nullable();
                $table->text('description')->nullable();
                $table->string('receipt_url')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 14. Staff / Employees Table
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('employee_id')->unique();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('department')->default('Operations');
                $table->string('designation')->default('Officer');
                $table->bigInteger('basic_salary_ugx')->default(1500000);
                $table->bigInteger('allowances_ugx')->default(200000);
                $table->string('tin_number')->nullable();
                $table->string('nssf_number')->nullable();
                $table->string('payment_method')->default('Bank');
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('mobile_money_number')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 15. Payroll & Payslips Table
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
                $table->string('payroll_period'); // e.g. "August 2026"
                $table->bigInteger('basic_salary_ugx');
                $table->bigInteger('allowances_ugx')->default(0);
                $table->bigInteger('gross_pay_ugx');
                $table->bigInteger('nssf_employee_ugx'); // 5% employee
                $table->bigInteger('nssf_employer_ugx'); // 10% employer
                $table->bigInteger('paye_tax_ugx'); // Uganda progressive PAYE
                $table->bigInteger('other_deductions_ugx')->default(0);
                $table->bigInteger('net_salary_ugx');
                $table->string('payment_status')->default('paid'); // draft, processed, paid
                $table->date('payment_date')->nullable();
                $table->string('payment_method')->default('Bank Transfer');
                $table->string('reference_no')->nullable();
                $table->timestamps();
            });
        }

        // 16. Tax Compliance Records Table (Uganda URA)
        if (!Schema::hasTable('tax_records')) {
            Schema::create('tax_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('tax_type'); // PAYE, NSSF, VAT, Local Excise Duty (LED)
                $table->string('period'); // e.g. "August 2026", "Q3 2026"
                $table->bigInteger('taxable_amount_ugx');
                $table->decimal('tax_rate_percent', 5, 2)->default(18.00);
                $table->bigInteger('tax_amount_ugx');
                $table->string('status')->default('pending'); // pending, filed, paid
                $table->date('due_date')->nullable();
                $table->date('filing_date')->nullable();
                $table->string('ura_prn')->nullable(); // Uganda Payment Registration Number
                $table->timestamps();
            });
        }

        // 17. Customers & Vendors Directory
        if (!Schema::hasTable('parties')) {
            Schema::create('parties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('name');
                $table->string('company')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('tin_number')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->default('Kampala');
                $table->string('type')->default('customer'); // customer, vendor
                $table->bigInteger('balance_ugx')->default(0);
                $table->timestamps();
            });
        }

        // 18. Sales & Purchase Orders
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('order_number')->unique();
                $table->string('type')->default('sales_order'); // sales_order, purchase_order
                $table->string('party_name');
                $table->bigInteger('total_amount_ugx');
                $table->string('status')->default('confirmed'); // draft, confirmed, fulfilled, cancelled
                $table->date('order_date');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Add business_id & is_super_admin to users if not present
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false)->after('password');
            }
            if (!Schema::hasColumn('users', 'business_id')) {
                $table->unsignedBigInteger('business_id')->nullable()->after('is_super_admin');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
        });

        // Add business_id to products and invoices if not present
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'business_id')) {
                $table->unsignedBigInteger('business_id')->nullable()->after('id');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'business_id')) {
                $table->unsignedBigInteger('business_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('parties');
        Schema::dropIfExists('tax_records');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('subscription_settings');
        Schema::dropIfExists('feature_usage');
        Schema::dropIfExists('subscription_events');
        Schema::dropIfExists('subscription_invoices');
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plan_features');
        Schema::dropIfExists('features');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('business_locations');
        Schema::dropIfExists('businesses');
    }
};
