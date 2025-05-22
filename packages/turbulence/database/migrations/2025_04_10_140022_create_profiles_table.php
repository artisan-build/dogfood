<?php

use ArtisanBuild\Turbulence\Enums\UserRoles;
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
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(config('turbulence.user_model'));
            $table->timestamps();
        });

        $user_model = config('turbulence.user_model');
        $user_table = (new $user_model)->getTable();

        Schema::table($user_table, function (Blueprint $table): void {
            $table->unsignedInteger('current_account_id');
            $table->string('role')->default(UserRoles::User->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');

        $user_model = config('turbulence.user_model');
        $user_table = (new $user_model)->getTable();

        Schema::table($user_table, function (Blueprint $table): void {
            $table->dropColumn('current_account_id');
        });
    }
};
