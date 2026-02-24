<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('amount');
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('is_confirmed');
            $table->dropColumn('confirmed_at');
        });
    }
};
