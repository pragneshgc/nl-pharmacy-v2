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
        Schema::create('Cookie', function (Blueprint $table) {
            $table->string('CKey')->default('')->index('cookie');
            $table->integer('UserID')->default(0);
            $table->integer('EDate')->default(0);
            $table->string('Hostname', 128)->default('');

            $table->primary(['CKey']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Cookie');
    }
};