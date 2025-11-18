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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->enum('payment_method', ['cod', 'card', 'upi', 'netbanking', 'wallet', 'emi', 'phonepe', 'paypal'])->default('cod');
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->string('payment_id')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('courier_service')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal', 'discount', 'shipping_charge', 'tax', 'total',
                'coupon_code', 'payment_method', 'payment_status', 'payment_id',
                'status', 'notes', 'admin_notes', 'confirmed_at', 'shipped_at',
                'delivered_at', 'tracking_number', 'courier_service'
            ]);
        });
    }
};
