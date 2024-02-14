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
        Schema::create('Correspondence', function (Blueprint $table) {
            $table->integer('Correspondence', true);
            $table->string('ClientID', 12)->nullable();
            $table->string('PrescriptionID', 12)->nullable()->index('PrescriptionID');
            $table->text('Message')->nullable();
            $table->integer('CreatedDate')->nullable();
            $table->integer('Status')->nullable()
                ->comment('1 - active');
            $table->string('Subject', 250)->nullable();
            $table->string('ReferenceNumber', 12)->nullable();
            $table->integer('UserID')->nullable();
            $table->integer('DoctorID')->nullable();
            $table->integer('Type')->nullable();

            $table->index(['UserID', 'DoctorID', 'Status'], 'DocUsrStatus');
            $table->index(['PrescriptionID', 'Correspondence'], 'pre_cor');
            $table->index(['Status', 'UserID'], 'status_userid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Correspondence');
    }
};