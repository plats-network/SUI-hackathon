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
        Schema::create('user_nft', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->integer('nft_mint_id')->nullable();
            $table->tinyInteger('type')->default(0)->nullable();
            $table->string('booth_id')->nullable();
            $table->string('task_id')->nullable();
            $table->string('session_id')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_nft');
    }
};
