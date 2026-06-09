<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('adresse'); // mpesa, orange_money
            $table->string('payment_phone')->nullable()->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_phone');
            $table->string('payment_proof')->nullable()->after('payment_reference'); // chemin de la capture
            $table->string('payment_status')->default('non_paye')->after('payment_proof');
            // non_paye | en_verification | payee | refusee
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_phone',
                'payment_reference',
                'payment_proof',
                'payment_status',
            ]);
        });
    }
};
