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
        Schema::create('ProductCode', function (Blueprint $table) {
            $table->integer('ProductCodeID', true);
            $table->string('Code', 100)->nullable();
            $table->string('FDBID', 48)->default('0')->index('FDBID');
            $table->string('Name', 200)->nullable();
            $table->integer('Type')->nullable()
                ->comment('1 - medicine, 2 - test kit');
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active, 2 - discontinued');
            $table->float('Quantity', 10, 0)->nullable();
            $table->string('Units', 20)->nullable();
            $table->integer('Fridge')->nullable()
                ->comment('0 - no, 1 - yes');
            $table->float('VAT', 12)->nullable();
            $table->integer('Pack')->nullable()
                ->comment('0 - no, 1 - yes');
            $table->integer('OTC')->nullable()
                ->comment('also called reclassification) -> 0 - POM, 1 - P');
            $table->integer('ProductType')->nullable();
            $table->integer('JVM')->nullable()->default(2)->comment('0 - Pouchable (Manual), 1 - Pouchable (Always), 2 - Pouchable (Disabled)');
            $table->integer('TariffCode')->nullable();
            $table->integer('PrintForm')->nullable()->default(0)
                ->comment('0 - no, 1 - yes (print pathology form)');

            $table->index(['Status', 'Code'], 'StatusCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ProductCode');
    }
};