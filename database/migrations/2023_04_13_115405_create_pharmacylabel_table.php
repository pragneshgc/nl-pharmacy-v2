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
        Schema::create('PharmacyLabel', function (Blueprint $table) {
            $table->integer('PharmacyLabelID', true);
            $table->integer('ProductID')->nullable()->index('PhrmacyLabelProductID');
            $table->text('Instruction')->nullable();
            $table->integer('Pack')->default(1);
            $table->integer('Type')->default(1);
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');
            $table->string('Code', 100)->nullable();
            $table->text('Description')->nullable();
            $table->integer('Dosage')->nullable();

            $table->index(['ProductID'], 'ProductID');
            $table->index(['ProductID', 'Dosage'], 'ProductIDDosage');
            $table->index(['ProductID', 'PharmacyLabelID'], 'ProductIDPharmacyLabelID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PharmacyLabel');
    }
};