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
            $table->string('code')->unique()->after('id');
            $table->string('discount_type')->default('percentage')->after('code');
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('discount_value');
            $table->decimal('min_purchase_amount', 10, 2)->nullable()->after('discount_percentage');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->after('min_purchase_amount');
            $table->integer('usage_limit')->nullable()->after('max_discount_amount');
            $table->integer('used_count')->default(0)->after('usage_limit');
            $table->timestamp('valid_from')->nullable()->after('used_count');
            $table->timestamp('valid_until')->nullable()->after('valid_from');
            $table->boolean('is_active')->default(true)->after('valid_until');
            $table->text('description')->nullable()->after('is_active');
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
