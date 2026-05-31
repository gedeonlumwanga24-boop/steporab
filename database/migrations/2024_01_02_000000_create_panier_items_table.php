<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('panier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panier_id')->constrained('paniers')->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2);
            $table->string('taille')->nullable();
            $table->string('couleur')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index pour accélérer les recherches
            $table->index(['panier_id', 'produit_id']);
            $table->unique(['panier_id', 'produit_id', 'taille'], 'unique_item_per_cart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panier_items');
    }
};
