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
        Schema::create('InvoiceItem', function (Blueprint $table) {
            $table->integer('ItemID', true);
            $table->integer('InvoiceID')->nullable()->index('InvoiceID');
            $table->string('ReferenceNumber', 20)->nullable();
            $table->integer('ProductID')->nullable()->index('ProductID');
            $table->string('Description', 250)->nullable();
            $table->string('ProductCode', 100)->nullable()->index('ProductCode');
            $table->float('UnitCost', 6)->nullable()->index('UnitCost');
            $table->integer('Quantity')->nullable()->index('Quantity');
            $table->integer('Type')->nullable()->index('Type')
                ->comment('1 - PRODUCT,2 - SHIPPING,3 - CREDIT,4 - CHARGE');
            $table->integer('Status')->nullable()->index('Status')
                ->comment('0 - processed, 1 - unprocessed');
            $table->float('VAT', 6, 3)->nullable()->index('VAT');
            $table->integer('PrescriptionID')->nullable()->index('PrescriptionID');
            $table->integer('Date')->nullable();
            $table->integer('DoctorID')->nullable();

            $table->unique(['InvoiceID', 'ReferenceNumber', 'ProductID', 'Description', 'ProductCode', 'UnitCost', 'Quantity', 'Type', 'Status', 'VAT', 'PrescriptionID'], 'removeAnyDupliateEntries');
            $table->index(['Type', 'InvoiceID'], 'TypeInvoiceID');
            $table->index(['Type', 'InvoiceID', 'PrescriptionID'], 'TypeInvoiceIDPrescriptionID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('InvoiceItem');
    }
};