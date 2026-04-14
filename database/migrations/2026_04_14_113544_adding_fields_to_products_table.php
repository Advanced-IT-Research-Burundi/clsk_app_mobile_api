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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'customs_price')) {
                $table->decimal('customs_price', 15, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'cbm')) {
                $table->decimal('cbm', 15, 4)->nullable()->after('customs_price');
            }
            if (!Schema::hasColumn('products', 'customs_price_currency')) {
                $table->enum('customs_price_currency', ['USD', 'BIF', 'RMB'])->default('USD')->after('cbm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('customs_price');
            $table->dropColumn('cbm');
        });
    }
};
