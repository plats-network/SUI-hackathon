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
        Schema::dropIfExists('setup_games');

        Schema::table('mini_games', function (Blueprint $table) {
            $table->string('banner_url')->nullable()->comment('Ảnh banner');
            $table->tinyInteger('type_prize')->default(0)->comment('Loại giải');
            $table->tinyInteger('num')->default(0)->comment('Số lượng giải');
            $table->tinyInteger('is_game')->default(0)->comment('Loại quay 0: vòng tròn, 1: random ô');
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
