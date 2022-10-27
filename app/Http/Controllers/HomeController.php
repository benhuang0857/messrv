<?php

namespace App\Http\Controllers;

use App\Batches;
use App\Tools;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $uId = $user->id;

        $batches = Batches::where('doer_id', $uId)->get();

        $state = [
            'pending'  => '尚未審核', 
            'approve'   => '審核通過',
            'disapprove'=> '審核未通過',
            'process'   => '進行中',
            'complete'  => '完成',
            'hold'      => '暫停',
            'cancel'    => '取消',
        ];

        $color = [
            'pending'  => 'orange', 
            'approve'   => '#3097D1',
            'disapprove'=> 'orange',
            'process'   => '#3097D1',
            'complete'  => 'green',
            'hold'      => 'orange',
            'cancel'    => 'red',
        ];

        $data = [
            'User'=>$user,
            'Batches'=>$batches,
            'States'=>$state,
            'Color'=>$color,
            'Tools'=>Tools::all(),
        ];

        return view('home')->with('Data', $data);
    }
}
