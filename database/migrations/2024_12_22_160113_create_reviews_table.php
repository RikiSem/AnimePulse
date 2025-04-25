<?php

use App\Models\Reviews;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('anime_id');
            $table->longText('html');
            $table->longText('text');
            $table->integer('final_mark');
            $table->text('midterm_mark');
            $table->integer('user_id');
            $table->string('status')->default(Reviews::DEFAULT_STATUS);
            $table->string('reject_reason')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
