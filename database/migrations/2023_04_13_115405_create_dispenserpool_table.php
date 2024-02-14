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
        Schema::create('DispenserPool', function (Blueprint $table) {
            $table->integer('DispenserPoolID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->integer('UserID')->nullable()->index('UserID');
            $table->string('Date', 12)->nullable();
            $table->integer('Type')->default(0);
            $table->integer('Status')->nullable()->index('Status')
                ->comment('0 - inactive, 1 - active');

            $table->index(['Status', 'UserID'], 'StatusUserID');
            $table->index(['Status', 'UserID', 'PrescriptionID'], 'StatusUserIDPrescriptionID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DispenserPool');
    }
};