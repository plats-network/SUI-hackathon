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
            $table->string('contract_task_events_details_id', 255)->nullable()->after('code')->comment('id của mạng bên web3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_event_details', function (Blueprint $table) {
            $table->dropColumn('contract_task_events_details_id');
        });
    }
};
