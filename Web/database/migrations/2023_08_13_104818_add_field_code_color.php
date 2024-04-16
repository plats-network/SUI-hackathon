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
        Schema::table('user_codes', function (Blueprint $table) {
            $table->string('hash_code')->nullable()->comment('Mã màu');
            $table->uuid('travel_game_id')->nullable()->comment('Default Null');
        });

        Schema::table('event_user_tickets', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('sesion_code');
            $table->dropColumn('booth_code');
            $table->dropColumn('qr_image');
            $table->dropColumn('is_session');
            $table->dropColumn('is_booth');
            $table->dropColumn('color_session');
            $table->dropColumn('color_boot');
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
