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
        Schema::create('app_module', function (Blueprint $table) {
            $table->foreignId('app_id')->constrained('apps');
            $table->foreignId('module_id')->constrained('modules');
            $table->tinyInteger('status')->comment('0=inactive, 1=active');

            $table->primary(['app_id', 'module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_module');
    }
};