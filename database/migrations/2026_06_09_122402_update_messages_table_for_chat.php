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
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('id');
            // 'message' devient nullable car l'admin ne remplit que 'reply' ? 
            // Non, l'admin remplira 'message' désormais. Mais pour l'instant 'message' est 'string' ou 'text'.
            // On laisse 'message' tel quel.
        });

        // Migration des anciennes réponses (replies) en nouveaux enregistrements
        $messagesWithReplies = \Illuminate\Support\Facades\DB::table('messages')
            ->whereNotNull('reply')
            ->where('reply', '!=', '')
            ->get();

        foreach ($messagesWithReplies as $msg) {
            \Illuminate\Support\Facades\DB::table('messages')->insert([
                'is_admin'   => true,
                'nom'        => 'Support Stepora',
                'email'      => $msg->email,
                'message'    => $msg->reply,
                'status'     => 'répondu',
                'created_at' => $msg->updated_at,
                'updated_at' => $msg->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
