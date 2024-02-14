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
        Schema::create('TestKit', function (Blueprint $table) {
            $table->increments('TestKitID');
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->string('ParentReferenceNumber', 12)->nullable();
            $table->string('ReferenceNumber', 20)->nullable()->index('ReferenceNumber');
            $table->integer('Type')->default(1);
            $table->integer('Total')->nullable();
            $table->integer('Count')->nullable();
            $table->string('Name', 100)->nullable();
            $table->string('Surname', 100)->nullable();
            $table->string('DOB', 10)->nullable();
            $table->string('Sex', 1)->nullable();
            $table->string('Postcode', 10)->nullable()->default('');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
            $table->string('Code', 100)->nullable();
            $table->string('Address1', 100)->nullable();
            $table->string('Address2', 100)->nullable();
            $table->string('Address3', 100)->nullable();
            $table->string('Address4', 100)->nullable();
            $table->string('Mobile', 50)->nullable();
            $table->integer('Status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TestKit');
    }
};