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
        Schema::table('mini_games', function (Blueprint $table) {
            $table->tinyInteger('type')->default(0)->comment('0: session, 1: booth');
            $table->boolean('is_vip')->default(false)->comment('false: normal, true: vip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mini_games', function (Blueprint $table) {
            //
        });
    }
};
