<?php

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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id');
            $table->string('name');
            $table->text('alter_names');
            $table->text('description')->nullable(true);
            $table->string('tags')->nullable(true);
            $table->string('season')->nullable(true);
            $table->string('release_date')->nullable(true);
            $table->integer('release_day')->nullable(true);
            $table->integer('release_month')->nullable(true);
            $table->integer('release_year')->nullable(true);
            $table->boolean('in_current_season')->nullable(true);
            $table->text('studio')->nullable(true);
            $table->string('type')->nullable(true);
            $table->string('status')->nullable(true);
            $table->integer('count_series')->nullable(true);
            $table->string('poster')->nullable(true);
            $table->integer('rate')->default(0);
            $table->text('other_rates')->nullable(true);
            $table->string('link_to_watch')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
