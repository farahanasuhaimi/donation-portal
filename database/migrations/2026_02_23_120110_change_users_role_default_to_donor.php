<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'donor'");
            DB::table('users')->whereNull('role')->update(['role' => 'donor']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'organizer'");
        }
    }
};
