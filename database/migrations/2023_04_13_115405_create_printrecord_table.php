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
        Schema::create('PrintRecord', function (Blueprint $table) {
            $table->integer('PrintRecordID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->integer('UserID')->nullable();
            $table->integer('TNTLabel')->nullable();
            $table->integer('DeliveryNote')->nullable();
            $table->integer('PharmacyLabel')->nullable();
            $table->integer('Invoice')->nullable();
            $table->integer('Consignment')->nullable();
            $table->integer('Type')->nullable();
            $table->integer('Status')->nullable();
            $table->integer('DoctorLetter')->nullable();
            $table->integer('UPSLabel')->nullable();

            $table->index(['PrescriptionID', 'DeliveryNote', 'PharmacyLabel'], 'PrescriptionIDDeliveryNotePharmacyLabel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PrintRecord');
    }
};