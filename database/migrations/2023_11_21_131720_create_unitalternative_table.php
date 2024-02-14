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
        Schema::create('UnitAlternative', function (Blueprint $table) {
            $table->increments('UnitAlternativeID');
            $table->unsignedInteger('ProductCodeID')->nullable()->index('ProductCodeID');
            $table->unsignedInteger('ClientID')->nullable()->index('ClientID');
            $table->string('AlternativeUnit')->nullable()->index('AlternativeUnit');
            $table->unsignedInteger('UserID')->index('UserID')->comment('Relates to the pharmacist user this alternative was added by');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
            $table->timestamp('DeletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UnitAlternative');
    }
};
