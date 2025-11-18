<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copy data from old tailoring_requests to new custom_tailoring_requests
        DB::statement("
            INSERT INTO custom_tailoring_requests (
                user_id, name, email, phone, cloth_material, sizes, color, 
                style, status, work_status, notes, created_at, updated_at
            )
            SELECT 
                user_id, name, email, phone, cloth_material, 
                COALESCE(size_details, '{}') as sizes,
                color, 
                style_type as style,
                LOWER(status) as status,
                NULL as work_status,
                additional_notes as notes,
                created_at, updated_at
            FROM tailoring_requests
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DELETE FROM custom_tailoring_requests WHERE id > 0");
    }
};
