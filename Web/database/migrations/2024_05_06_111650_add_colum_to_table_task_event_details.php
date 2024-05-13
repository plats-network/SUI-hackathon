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
        Schema::table('task_event_details', function (Blueprint $table) {
            $table->tinyInteger('amount')->nullable()->comment('số lượng của từng loại vé theo session, booth');
            $table->tinyInteger('avaliable_amount')->nullable()->comment('số lượng đã sử dụng của từng loại vé theo session, booth');
            $table->string('photo')->nullable()->comment('ảnh của từng session, booth'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_event_details', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('avaliable_amount');
            $table->dropColumn('photo');
        });
    }
};
