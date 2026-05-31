<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'admin_response')) {
                $table->text('admin_response')->nullable()->after('admin_notes');
            }

            if (!Schema::hasColumn('messages', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('admin_response');
            }

            if (!Schema::hasColumn('messages', 'response_read_at')) {
                $table->timestamp('response_read_at')->nullable()->after('responded_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            foreach (['admin_response', 'responded_at', 'response_read_at'] as $column) {
                if (Schema::hasColumn('messages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
