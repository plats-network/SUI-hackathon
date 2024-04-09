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
        Schema::table('task_events', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Schema::table('event_user_tickets', function (Blueprint $table) {
            $table->boolean('is_session')->default(false)->comment('Trạng thái quay thưởng');
            $table->boolean('is_booth')->default(false)->comment('Trạng thái quay thưởng');
            $table->string('color_session')->nullable();
            $table->string('color_boot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_events', function (Blueprint $table) {
            //
        });
    }
};
