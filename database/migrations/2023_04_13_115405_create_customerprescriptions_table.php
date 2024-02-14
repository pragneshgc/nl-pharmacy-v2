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
        Schema::create('CustomerPrescriptions', function (Blueprint $table) {
            $table->integer('CustomerPrescriptionID', true);
            $table->string('Name', 100)->nullable();
            $table->string('Surname', 100)->nullable();
            $table->string('DOB', 10)->nullable();
            $table->string('Sex', 1)->nullable();
            $table->text('Prescriptions')->nullable();
            $table->integer('ModifiedDate')->nullable();
            $table->integer('CreatedDate')->nullable();
            $table->integer('AccessedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CustomerPrescriptions');
    }
};