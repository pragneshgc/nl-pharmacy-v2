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
        Schema::create('UPSAccessPoint', function (Blueprint $table) {
            $table->integer('UPSAccessPointID', true);
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->string('Name', 80)->nullable();
            $table->string('Address1', 50)->nullable();
            $table->string('Address2', 50)->nullable();
            $table->string('Address3', 50)->nullable();
            $table->string('Address4', 50)->nullable();
            $table->string('Postcode', 50)->nullable();
            $table->string('CountryCode', 3)->nullable();
            $table->integer('APNotificationType')->nullable();
            $table->string('APNotificationValue', 50)->nullable();
            $table->string('APNotificationFailedEmailAddress', 50)->nullable();
            $table->string('APNotificationCountryTerritory', 2)->nullable();
            $table->string('APNotificationPhoneCountryCode', 2)->nullable();
            $table->string('APNotificationLanguage', 3)->nullable();
            $table->string('UPSAccessPoint', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('UPSAccessPoint');
    }
};