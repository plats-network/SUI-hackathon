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
        Schema::table('travel_games', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->comment('Default Null');
        });

        Schema::table('user_codes', function (Blueprint $table) {
            $table->renameColumn('task_id', 'task_event_id');
            $table->boolean('is_prize')->default(false);
            $table->string('name_prize')->nullable()->comment('Tên giải');
            $table->boolean('is_vip')->default(false)->comment('0: normal, 1: vip');
        });

        Schema::create('mini_games', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('task_event_id');
            $table->uuid('travel_game_id');
            $table->string('code')->nullable();
            $table->boolean('status')->default(true)->comment('0: Disable, 1: Enable');
            $table->timestamps();
        });

        Schema::create('setup_games', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('task_event_id');
            $table->uuid('travel_game_id');
            $table->tinyInteger('type_prize')->default(0)->comment('Loại giải');
            $table->tinyInteger('num')->default(0)->comment('Số lượng giải');
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
        //
    }
};
