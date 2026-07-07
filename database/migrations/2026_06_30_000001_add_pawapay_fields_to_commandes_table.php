<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les champs nécessaires au système de paiement automatisé PawaPay.
     * - pawapay_deposit_id    : UUID unique généré par Stepora, envoyé à PawaPay
     * - mobile_money_provider : Code opérateur PawaPay (VODACOM_DRC, AIRTEL_DRC, ORANGE_DRC)
     * - mobile_money_number   : Numéro international du client (ex: 243812345678)
     */
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('pawapay_deposit_id', 36)->nullable()->unique()->after('payment_proof');
            $table->string('mobile_money_provider', 50)->nullable()->after('pawapay_deposit_id');
            $table->string('mobile_money_number', 20)->nullable()->after('mobile_money_provider');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropUnique(['pawapay_deposit_id']);
            $table->dropColumn(['pawapay_deposit_id', 'mobile_money_provider', 'mobile_money_number']);
        });
    }
};
