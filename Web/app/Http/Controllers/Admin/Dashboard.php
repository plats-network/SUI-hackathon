<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Services\Admin\{EventService, TaskService};
use Illuminate\Support\Facades\Schema;

class Dashboard extends Controller
{
    public function __construct(
        private TaskService  $taskService,
    )
    {
        // $this->middleware('client_admin');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return redirect()->route('cws.dashboard');

        $limit = $request->get('limit') ?? 10;
        $events = $this->taskService->search([
            'limit' => $limit,
            'type' => EVENT
        ]);

        return view('cws.home', [
            'events' => $events
        ]);
    }

    //ping
    public function ping()
    {
        return response()->json([
            'status' => 'success',
            'updated_at' => '27.11.2023',
            'message' => 'pong'
        ]);
    }

    //updateDb
    //Method: GET
    //Url: /api/update-db
    public function updateDb(Request $request)
    {
        //Show all colum from table card_datas
        //Schema::getColumnListing('card_datas');


        //Update user
        if ($request->input('update') == 2) {
            Schema::table('users', function ($table) {
                $table->string('wallet_name')->nullable();
                $table->string('wallet_address')->nullable();
                $table->string('view_count')->nullable();
            });
        }

        if ($request->input('update') == 3) {
            Schema::table('event_user_tickets', function ($table) {
                //Change phone to string
                $table->string('phone')->nullable()->change();
            });
        }

        if ($request->input('update') == 1) {
            //Unknown column 'intro_text' in 'field list' (Connection: mysql, SQL: insert into infos (
            Schema::table('nft', function ($table) {
                //Task id
                $table->uuid('user_id')->nullable();
                $table->uuid('task_id')->nullable();

                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->string('image_url')->nullable();
                $table->string('permalink')->nullable();
                $table->string('asset_contract')->nullable();
                $table->string('collection_id')->nullable();
                //owner_id
                $table->uuid('owner_id')->nullable();
                //contract_date
                $table->string('contract_date')->nullable();
                //Blockchain
                $table->string('blockchain')->nullable();
                //IS send
                $table->string('is_send')->nullable();
            });
        }

        return 1;
    }
}
