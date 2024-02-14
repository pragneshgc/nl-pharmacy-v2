<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('PharmacyUser', function (Blueprint $table) {
            $table->foreignId('pharmacy_role_id')
                ->nullable()
                ->after('default_app')
                ->constrained('roles');
            $table->foreignId('inventory_role_id')
                ->nullable()
                ->after('pharmacy_role_id')
                ->constrained('roles');
            $table->foreignId('shipping_role_id')
                ->nullable()
                ->after('inventory_role_id')
                ->constrained('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('PharmacyUser', function (Blueprint $table) {
            $table->dropColumn('pharmacy_role_id');
            $table->dropColumn('inventory_role_id');
            $table->dropColumn('shipping_role_id');
        });
    }
};