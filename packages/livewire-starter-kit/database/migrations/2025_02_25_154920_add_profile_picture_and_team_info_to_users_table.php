<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = DB::connection()
                ->getSchemaBuilder()
                ->getColumnListing("users");

            if (! in_array('profile_photo_path', $columns, true)) {
                $table->string('profile_photo_path', 2048)->nullable();
            }

            if (! in_array('current_team_id', $columns, true)) {
                $table->string('current_team_id', 2048)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
