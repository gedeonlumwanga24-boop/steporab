<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande_produit', function (Blueprint $table) {
            $table->id();

            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');

            $table->integer('quantite');
            $table->integer('prix_unitaire');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_produit');
    }
};