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
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'code')) {
                $table->string('code')->unique()->after('id');
            }
            if (!Schema::hasColumn('coupons', 'discount_type')) {
                $table->string('discount_type')->default('percentage')->after('code');
            }
            if (!Schema::hasColumn('coupons', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            }
            if (!Schema::hasColumn('coupons', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_value');
            }
            if (!Schema::hasColumn('coupons', 'min_purchase_amount')) {
                $table->decimal('min_purchase_amount', 10, 2)->nullable()->after('discount_percentage');
            }
            if (!Schema::hasColumn('coupons', 'max_discount_amount')) {
                $table->decimal('max_discount_amount', 10, 2)->nullable()->after('min_purchase_amount');
            }
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                $table->integer('usage_limit')->nullable()->after('max_discount_amount');
            }
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->integer('used_count')->default(0)->after('usage_limit');
            }
            if (!Schema::hasColumn('coupons', 'valid_from')) {
                $table->timestamp('valid_from')->nullable()->after('used_count');
            }
            if (!Schema::hasColumn('coupons', 'valid_until')) {
                $table->timestamp('valid_until')->nullable()->after('valid_from');
            }
            if (!Schema::hasColumn('coupons', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('valid_until');
            }
            if (!Schema::hasColumn('coupons', 'description')) {
                $table->text('description')->nullable()->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'discount_type',
                'discount_value',
                'discount_percentage',
                'min_purchase_amount',
                'max_discount_amount',
                'usage_limit',
                'used_count',
                'valid_from',
                'valid_until',
                'is_active',
                'description'
            ]);
        });
    }
};
