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
        Schema::create('Note', function (Blueprint $table) {
            $table->comment('Stores notes related to customers');
            $table->increments('NoteID');
            $table->unsignedInteger('ParentNoteID')->nullable()->index('ParentNoteID');
            $table->unsignedInteger('CustomerID')->nullable()->index('CustomerID')->comment('Relates to the customer table for later use');
            $table->unsignedInteger('PrescriptionID')->nullable()->index('PrescriptionID')->comment('Relates to the prescription this note was added on');
            $table->string('ReferenceNumber', 20)->nullable()->index('ReferenceNumber');
            $table->string('Subscription', 20)->nullable()->index('Subscription');
            $table->unsignedInteger('UserID')->index('UserID')->comment('Relates to the ESA user this note was added by');
            $table->unsignedInteger('DeletedByUserID')->nullable()->index('DeletedByUserID');
            $table->unsignedInteger('EditedByUserID')->nullable()->index('EditedByUserID');
            $table->integer('Type')->default(1)->comment('1 - Critical, 2 - Medical Information, 3 - Other, 4 - prescription specific');
            $table->integer('OrderSpecific')->default(0);
            $table->integer('Alert')->default(0);
            $table->integer('Pending')->default(0)
                ->comment('0 - not pending, 1 - pending (the order with the specified reference number has not yet been recieved by the pharmacy)');
            $table->text('Note')->nullable();
            $table->timestamp('CreatedAt')->nullable()->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrentOnUpdate()->nullable();
            $table->timestamp('DeletedAt')->nullable();
            $table->timestamp('EditedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Note');
    }
};