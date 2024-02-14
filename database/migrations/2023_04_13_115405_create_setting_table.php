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
        Schema::create('Setting', function (Blueprint $table) {
            $table->integer('SettingID', true);
            $table->string('Name', 50)->nullable();
            $table->text('Value')->nullable();
            $table->integer('Type')->nullable()
                ->comment('1 - records per page, 2 - general app setting, 900 - hidden, 901 - fmd configuration, 902 - safe ip mailing list, 903 - shipping settings');
            $table->integer('Status')->nullable()
                ->comment('0 - inactive, 1 - active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Setting');
    }
};