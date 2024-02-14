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
        Schema::create('Questionnaire', function (Blueprint $table) {
            $table->integer('QuestionnaireID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->text('Question')->nullable();
            $table->text('Answer')->nullable();
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Questionnaire');
    }
};