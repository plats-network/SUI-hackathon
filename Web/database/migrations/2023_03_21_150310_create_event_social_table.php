<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_social', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('task_id')->nullable();
            $table->string('url')->nullable();
            $table->string('text')->nullable();
            $table->boolean('is_comment')->default(false);
            $table->boolean('is_like')->default(false);
            $table->boolean('is_retweet')->default(false);
            $table->boolean('is_tweet')->default(false);
            $table->tinyInteger('type')->default(0)->comment('0:twitter, 1:...');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_social');
    }
};
