<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Tray', function (Blueprint $table) {
            $table->integer('TrayID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->integer('UserID')->nullable()->index('UserID');
            $table->integer('Type')->nullable()
                ->comment('0 - inactive, 1 - active');
            $table->integer('Priority')->nullable()->default(1)
                ->comment('1 - Pharmacist Tray, 2 - Dispenser Tray');
            $table->integer('Status')->nullable()->index('Status');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->nullable();
            $table->timestamp('ProcessedAt')->nullable();
            $table->timestamp('DeletedAt')->nullable();

            $table->index(['Status', 'UserID'], 'StatusUserID');
            $table->index(['Status', 'UserID', 'PrescriptionID'], 'StatusUserIDPrescriptionID');
            $table->index(['Type', 'Status', 'UserID'], 'Type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Tray');
    }
};