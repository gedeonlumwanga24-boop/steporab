<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('messages', 'sujet')) {
                $table->string('sujet')->default('Demande generale')->after('email');
            }

            if (!Schema::hasColumn('messages', 'statut')) {
                $table->string('statut')->default('nouveau')->after('message');
            }

            if (!Schema::hasColumn('messages', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('statut');
            }

            if (!Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('admin_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            foreach (['sujet', 'statut', 'admin_notes', 'read_at'] as $column) {
                if (Schema::hasColumn('messages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
