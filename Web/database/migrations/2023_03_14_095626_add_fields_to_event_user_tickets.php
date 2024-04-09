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
        Schema::table('event_user_tickets', function (Blueprint $table) {
            $table->boolean('is_checkin')->default(false)->comment('Check user đã join event chưa');
            $table->string('hash_code')->nullable()->comment('');
            $table->string('sesion_code')->nullable()->comment('Mã quay thưởng session');
            $table->string('booth_code')->nullable()->comment('Mã quay thưởng booth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user_tickets', function (Blueprint $table) {
            //
        });
    }
};
