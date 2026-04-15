<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depenses_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 15, 2);
            $table->text('description')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->decimal('rate', 15, 4)->default(1); // taux vers BIF
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depenses_produits');
    }
};
