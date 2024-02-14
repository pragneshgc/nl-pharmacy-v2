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
        Schema::create('ProductNameAlternative', function (Blueprint $table) {
            $table->increments('ProductNameAlternativeID');
            $table->unsignedInteger('ProductCodeID')->nullable()->index('ProductCodeID');
            $table->unsignedInteger('ClientID')->nullable()->index('ClientID');
            $table->string('AlternativeName')->nullable()->index('AlternativeName');
            $table->unsignedInteger('UserID')->index('UserID')->comment('Relates to the pharmacist user this alternative was added by');
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
            $table->timestamp('DeletedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ProductNameAlternative');
    }
};