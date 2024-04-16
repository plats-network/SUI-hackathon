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
        Schema::create('travel_games', function (Blueprint $table) {
            $table->uuid('id');
            $table->tinyInteger('type')->default(0)->comment('0: Default, 1: Near, 2: Plats');
            $table->string('name')->nullable();
            $table->datetime('prize_at')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(true)->comment('Trạng thái hoạch động');
            $table->timestamps();
        });

        Schema::table('task_event_details', function (Blueprint $table) {
            $table->uuid('travel_game_id')->nullable()->comment('Default null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_games');
    }
};
