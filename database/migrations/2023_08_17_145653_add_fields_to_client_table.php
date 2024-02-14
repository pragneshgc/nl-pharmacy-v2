<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('Client', function (Blueprint $table) {
            $table->decimal('DispensingFee', 6, 2)->default(0)->after('CreditLimit');
            $table->text('DisplayVAT')->nullable()->after('VAT');
            $table->string('InvoiceEmail', 100)->nullable()->after('ITEmail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Client', function (Blueprint $table) {
            $table->dropColumn('DispensingFee');
            $table->dropColumn('DisplayVAT');
            $table->dropColumn('InvoiceEmail');
        });
    }
};
