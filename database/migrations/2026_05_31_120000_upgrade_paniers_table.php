<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('paniers', function (Blueprint $table) {
            if (! Schema::hasColumn('paniers', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('paniers', 'session_id')) {
                $table->string('session_id')->nullable()->after('user_id')->index();
            }
            if (! Schema::hasColumn('paniers', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('session_id');
            }
            if (! Schema::hasColumn('paniers', 'status')) {
                $table->enum('status', ['active', 'abandoned', 'converted'])->default('active')->after('total')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('paniers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'session_id', 'total', 'status']);
        });
    }
};
