<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 3)->unique();
                $table->string('name');
                $table->string('symbol', 10);
                $table->decimal('exchange_rate', 18, 6)->default(1.000000); // 1 USD = X Currency
                $table->boolean('is_active')->default(true);
                $table->string('country');
                $table->string('flag', 10)->default('🌐');
                $table->string('format')->default('standard');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('sku')->unique();
                $table->text('description')->nullable();
                $table->bigInteger('price_cents'); // Base price in USD cents (lowest denomination)
                $table->integer('stock_quantity')->default(0);
                $table->string('category')->default('Enterprise Core');
                $table->boolean('is_active')->default(true);
                $table->string('image_url')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('project_name')->default('General Operations');
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['backlog', 'in_progress', 'review', 'completed'])->default('in_progress');
                $table->bigInteger('price_cents')->default(0); // Estimated budget/value in integer cents
                $table->date('due_date')->nullable();
                $table->string('assigned_user')->nullable();
                $table->string('client_name')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->text('billing_address')->nullable();
                $table->date('invoice_date');
                $table->date('due_date');
                $table->string('currency_code', 3)->default('USD');
                $table->decimal('exchange_rate_applied', 18, 6)->default(1.000000);
                $table->bigInteger('subtotal_cents');
                $table->bigInteger('tax_cents')->default(0);
                $table->bigInteger('total_cents');
                $table->enum('status', ['draft', 'pending', 'paid', 'overdue', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'customer_name')) {
                    $table->string('customer_name')->default('Valued Client')->after('invoice_number');
                }
                if (!Schema::hasColumn('invoices', 'customer_email')) {
                    $table->string('customer_email')->default('billing@client.com')->after('customer_name');
                }
                if (!Schema::hasColumn('invoices', 'billing_address')) {
                    $table->text('billing_address')->nullable()->after('customer_email');
                }
                if (!Schema::hasColumn('invoices', 'invoice_date')) {
                    $table->date('invoice_date')->nullable()->after('billing_address');
                }
                if (!Schema::hasColumn('invoices', 'currency_code')) {
                    $table->string('currency_code', 10)->default('UGX')->after('due_date');
                }
                if (!Schema::hasColumn('invoices', 'exchange_rate_applied')) {
                    $table->decimal('exchange_rate_applied', 18, 6)->default(1.000000)->after('currency_code');
                }
                if (!Schema::hasColumn('invoices', 'subtotal_cents')) {
                    $table->bigInteger('subtotal_cents')->default(0)->after('exchange_rate_applied');
                }
                if (!Schema::hasColumn('invoices', 'tax_cents')) {
                    $table->bigInteger('tax_cents')->default(0)->after('subtotal_cents');
                }
                if (!Schema::hasColumn('invoices', 'total_cents')) {
                    $table->bigInteger('total_cents')->default(0)->after('tax_cents');
                }
                if (!Schema::hasColumn('invoices', 'notes')) {
                    $table->text('notes')->nullable()->after('status');
                }
            });
        }

        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->bigInteger('unit_price_cents');
                $table->bigInteger('total_price_cents');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('company')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'])->default('new');
                $table->bigInteger('estimated_value_cents')->default(0);
                $table->string('assigned_to')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
                $table->string('requester_name');
                $table->string('requester_email')->nullable();
                $table->string('assigned_agent')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('products');
        Schema::dropIfExists('currencies');
    }
};
