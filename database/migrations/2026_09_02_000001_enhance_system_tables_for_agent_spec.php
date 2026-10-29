<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to enhance system tables for agent.md specifications.
     */
    public function up(): void
    {
        // 1. Ensure users table has email_verified_at
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });

        // Ensure legacy columns in invoices are nullable
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('invoices', 'date')) {
                $table->date('date')->nullable()->default(null)->change();
            }
            if (Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 15, 4)->nullable()->default(0)->change();
            }
            if (Schema::hasColumn('invoices', 'balance_due')) {
                $table->decimal('balance_due', 15, 4)->nullable()->default(0)->change();
            }
        });

        // 2. Enhance products with cost_price_cents and vat_rate
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'cost_price_cents')) {
                $table->bigInteger('cost_price_cents')->default(0)->after('price_cents');
            }
            if (!Schema::hasColumn('products', 'vat_rate')) {
                $table->decimal('vat_rate', 5, 2)->default(18.00)->after('category');
            }
        });

        // 3. Stock Movements Table (Inventory Transaction History)
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('type'); // opening, purchase, sale, adjustment, return, pos
                $table->string('reference')->nullable(); // e.g. PO-001, INV-001, ADJ-102
                $table->integer('quantity_change'); // positive or negative
                $table->integer('balance_after');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 4. Quotes & Quote Items (Sales Module)
        if (!Schema::hasTable('quotes')) {
            Schema::create('quotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('quote_number')->unique();
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_email')->nullable();
                $table->date('quote_date');
                $table->date('expiry_date')->nullable();
                $table->bigInteger('subtotal_ugx')->default(0);
                $table->decimal('vat_rate_percent', 5, 2)->default(18.00);
                $table->bigInteger('vat_amount_ugx')->default(0);
                $table->bigInteger('discount_amount_ugx')->default(0);
                $table->bigInteger('total_amount_ugx')->default(0);
                $table->string('status')->default('sent'); // draft, sent, accepted, rejected, converted
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('quote_items')) {
            Schema::create('quote_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                $table->string('item_name');
                $table->integer('quantity')->default(1);
                $table->bigInteger('unit_price_ugx');
                $table->bigInteger('total_price_ugx');
                $table->timestamps();
            });
        }

        // 5. Credit Notes (Sales Returns)
        if (!Schema::hasTable('credit_notes')) {
            Schema::create('credit_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('credit_note_number')->unique();
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->string('customer_name');
                $table->date('credit_date');
                $table->bigInteger('amount_ugx');
                $table->text('reason')->nullable();
                $table->string('status')->default('issued'); // draft, issued, applied
                $table->boolean('restock_inventory')->default(true);
                $table->timestamps();
            });
        }

        // 6. Bills & Bill Items (Purchases Module)
        if (!Schema::hasTable('bills')) {
            Schema::create('bills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('bill_number')->unique();
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
                $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete(); // link to PO
                $table->string('vendor_name');
                $table->date('bill_date');
                $table->date('due_date')->nullable();
                $table->bigInteger('subtotal_ugx')->default(0);
                $table->decimal('vat_rate_percent', 5, 2)->default(18.00);
                $table->bigInteger('vat_amount_ugx')->default(0);
                $table->bigInteger('total_amount_ugx')->default(0);
                $table->bigInteger('paid_amount_ugx')->default(0);
                $table->bigInteger('balance_ugx')->default(0);
                $table->string('status')->default('pending'); // pending, partially_paid, paid, overdue
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bill_items')) {
            Schema::create('bill_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                $table->string('item_name');
                $table->integer('quantity')->default(1);
                $table->bigInteger('unit_cost_ugx');
                $table->bigInteger('total_cost_ugx');
                $table->timestamps();
            });
        }

        // 7. Payments Made (To Suppliers against Bills)
        if (!Schema::hasTable('payments_made')) {
            Schema::create('payments_made', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('payment_number')->unique();
                $table->foreignId('bill_id')->nullable()->constrained('bills')->nullOnDelete();
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
                $table->string('vendor_name');
                $table->bigInteger('amount_ugx');
                $table->date('payment_date');
                $table->string('payment_method')->default('Bank Transfer'); // Bank, Cash, Mobile Money
                $table->string('reference_no')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 8. Payments Received (From Customers against Invoices)
        if (!Schema::hasTable('payments_received')) {
            Schema::create('payments_received', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('payment_number')->unique();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->foreignId('party_id')->nullable()->constrained('parties')->nullOnDelete();
                $table->string('customer_name');
                $table->bigInteger('amount_ugx');
                $table->date('payment_date');
                $table->string('payment_method')->default('Cash'); // Cash, MTN MoMo, Airtel Money, Bank Transfer, Card
                $table->string('reference_no')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 9. Sales Receipts Table (Enhance or Create)
        if (!Schema::hasTable('sales_receipts')) {
            Schema::create('sales_receipts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('receipt_number')->unique();
                $table->foreignId('customer_id')->nullable();
                $table->string('customer_name')->default('Walk-in Customer');
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->date('date');
                $table->string('payment_mode')->default('Cash');
                $table->bigInteger('subtotal_ugx')->default(0);
                $table->bigInteger('vat_amount_ugx')->default(0);
                $table->bigInteger('total');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('sales_receipts', function (Blueprint $table) {
                if (!Schema::hasColumn('sales_receipts', 'business_id')) {
                    $table->unsignedBigInteger('business_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('sales_receipts', 'customer_name')) {
                    $table->string('customer_name')->default('Walk-in Customer')->after('customer_id');
                }
                if (!Schema::hasColumn('sales_receipts', 'invoice_id')) {
                    $table->unsignedBigInteger('invoice_id')->nullable()->after('customer_name');
                }
                if (!Schema::hasColumn('sales_receipts', 'subtotal_ugx')) {
                    $table->bigInteger('subtotal_ugx')->default(0)->after('payment_mode');
                }
                if (!Schema::hasColumn('sales_receipts', 'vat_amount_ugx')) {
                    $table->bigInteger('vat_amount_ugx')->default(0)->after('subtotal_ugx');
                }
                if (!Schema::hasColumn('sales_receipts', 'notes')) {
                    $table->text('notes')->nullable()->after('total');
                }
                if (!Schema::hasColumn('sales_receipts', 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable()->after('notes');
                }
                if (!Schema::hasColumn('sales_receipts', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // 10. Feedback System Table
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
                $table->string('subject');
                $table->string('category')->default('General Feedback'); // Suggestion, Bug, Feature Request, Question
                $table->text('message');
                $table->string('status')->default('pending'); // pending, reviewed, resolved
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('payments_made');
        Schema::dropIfExists('payments_received');
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('stock_movements');
    }
};
