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
        Schema::create('Invoice', function (Blueprint $table) {
            $table->integer('InvoiceID', true);
            $table->integer('ParentInvoiceID')->nullable();
            $table->integer('SequenceID')->nullable();
            $table->integer('DateCreated')->nullable();
            $table->integer('DatePaid')->nullable();
            $table->integer('ClientID')->nullable();
            $table->float('GrossAmount', 12)->nullable();
            $table->float('AmountReceived', 12)->nullable();
            $table->integer('Status')->nullable()
                ->comment('0 - INCOMPLETE,1 - UNPAID,2 - PAID,3 - CREDITNOTE,4 - DELETE');
            $table->integer('Type')->nullable()
                ->comment('0 - ESA, 1 - PXP');
            $table->integer('PaymentMethod')->nullable();
            $table->float('VAT', 6, 3)->nullable();
            $table->float('NetAmount', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Invoice');
    }
};