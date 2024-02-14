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
        Schema::create('Attachment', function (Blueprint $table) {
            $table->integer('AttachmentID', true);
            $table->integer('UserID')->nullable()->index('UserID');
            $table->integer('ReferenceID')->nullable()->index('PrescriptionID');
            $table->string('Name', 200)->nullable();
            $table->string('Filename', 200)->nullable();
            $table->integer('Type')->nullable()->index('Type')->comment('1 - scan errors, 2 - import log, 3 - item logs');
            $table->integer('Status')->nullable()->index('Status');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->useCurrent();
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
        Schema::dropIfExists('Attachment');
    }
};