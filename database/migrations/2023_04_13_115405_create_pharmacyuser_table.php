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
        Schema::create('PharmacyUser', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('esa_user_id')->nullable()->index('esa_user_id')->comment('User equivalent for the ESA user table');
            $table->unsignedInteger('role')->default(10);
            $table->unsignedInteger('inventory_role')->default(0);
            $table->unsignedInteger('shipping_role')->default(0);
            $table->string('default_app', 50)->default('pharmacy');
            $table->string('name');
            $table->string('surname');
            $table->string('email', 50)->unique('email');
            $table->string('password');
            $table->rememberToken();
            $table->string('code')->nullable()->default('');
            $table->string('token')->nullable()->default('');
            $table->text('two_factor_secret')->nullable();
            $table->string('viewing')->nullable()->default('');
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PharmacyUser');
    }
};