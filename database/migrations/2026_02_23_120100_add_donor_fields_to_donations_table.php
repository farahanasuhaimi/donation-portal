<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('donor_user_id')->nullable()->after('campaign_id')->constrained('users')->nullOnDelete();
            $table->string('donor_real_name')->nullable()->after('donor_name');
            $table->string('donor_alias_name')->nullable()->after('donor_real_name');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donor_user_id');
            $table->dropColumn('donor_real_name');
            $table->dropColumn('donor_alias_name');
        });
    }
};
