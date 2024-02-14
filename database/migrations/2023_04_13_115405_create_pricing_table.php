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
        Schema::create('Pricing', function (Blueprint $table) {
            $table->integer('PricingID', true);
            $table->string('Code', 100)->nullable()->index('code');
            $table->integer('ClientID')->nullable();
            $table->float('Price', 6)->nullable();
            $table->integer('Type')->nullable()
                ->comment('1 - product, 2 - shipping');
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');
            $table->float('Quantity', 6)->nullable();

            $table->index(['ClientID', 'Code', 'Quantity'], 'clientid_code_qty');
            $table->index(['Code', 'Quantity'], 'code_qty');
            $table->index(['Type', 'ClientID', 'Code'], 'type_clientid_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Pricing');
    }
};