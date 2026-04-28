<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection(config('sqlite-vector.connection'))
            ->create(config('sqlite-vector.metadata_table_name'), function (Blueprint $table) {
                $table->id();
                $table->morphs('embeddable');
                $table->json('metadata')->nullable();
                $table->string('source')->nullable();
                $table->string('model')->nullable();
                $table->timestamp('embedded_at')->nullable();
                $table->timestamps();

                $table->index(['embeddable_type', 'embeddable_id'], 'idx_embeddable');
            });
    }

    public function down(): void
    {
        Schema::connection(config('sqlite-vector.connection'))
            ->dropIfExists(config('sqlite-vector.metadata_table_name'));
    }
};
