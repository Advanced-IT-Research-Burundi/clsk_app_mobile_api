<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'total_bif')) {
                $table->decimal('total_bif', 15, 2)->default(0)->after('customs_price_currency');
            }
            if (!Schema::hasColumn('products', 'total_usd')) {
                $table->decimal('total_usd', 15, 2)->default(0)->after('total_bif');
            }
            if (!Schema::hasColumn('products', 'total_rmb')) {
                $table->decimal('total_rmb', 15, 2)->default(0)->after('total_usd');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['total_bif', 'total_usd', 'total_rmb']);
        });
    }
};
