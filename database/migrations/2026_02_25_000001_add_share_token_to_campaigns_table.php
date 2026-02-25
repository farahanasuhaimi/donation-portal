<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('share_token', 10)->unique()->nullable()->after('id');
        });

        $campaignIds = DB::table('campaigns')
            ->whereNull('share_token')
            ->pluck('id');

        foreach ($campaignIds as $campaignId) {
            do {
                $token = Str::lower(Str::random(10));
                $exists = DB::table('campaigns')->where('share_token', $token)->exists();
            } while ($exists);

            DB::table('campaigns')->where('id', $campaignId)->update([
                'share_token' => $token,
            ]);
        }

    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropUnique(['share_token']);
            $table->dropColumn('share_token');
        });
    }
};
