<?php

namespace App\Http\Controllers;

use App\Batches;
use App\Tools;
use App\User;
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
        if (Auth::user()->state != 1) {
            return redirect('/');
        }

        $user = Auth::user();
        $uId = $user->id;

        $batches = Batches::orwhere('doer_id', $uId)
                            ->orwhere('area', $user->department)
                            ->get();

        $batchesNotStart = Batches::where('area', $user->department)
                            ->where('state', 'pending')
                            ->get();
        $jobNotStartCount = count($batchesNotStart);

        $batchesNotComplete = Batches::where('doer_id', $uId)
                            ->where('state', '<>', 'complete')
                            ->get();
        $jobCount = count($batchesNotComplete);

        $batchesHold = Batches::where('doer_id', $uId)
                            ->where('state', 'hold')
                            ->get();
        $jobHoldCount = count($batchesHold);

        $batchesComplete = Batches::where('doer_id', $uId)
                            ->where('state', 'complete')
                            ->get();
        $jobCmpleteCount = count($batchesComplete);

        $batchesProcess = Batches::where('doer_id', $uId)
                            ->where('state', 'process')
                            ->get();
        $jobProcessCount = count($batchesProcess);
        
        $doers = User::where('department', $user->department)->get();

        $state = [
            'pending'   => '確認中', 
            'approve'   => '等待加工',
            'disapprove'=> '取消加工',
            'process'   => '加工中',
            'complete'  => '已完成',
            'starthold' => '暫停',
            'endhold'   => '暫停',
            'cancel'    => '取消',
        ];

        $color = [
            'pending'   => 'orange', 
            'approve'   => '#3097D1',
            'disapprove'=> 'orange',
            'process'   => '#3097D1',
            'complete'  => 'green',
            'starthold' => 'orange',
            'endhold'   => 'orange',
            'cancel'    => 'red',
        ];

        $data = [
            'User'=>$user,
            'Batches'=>$batches,
            'States'=>$state,
            'Color'=>$color,
            'Tools'=>Tools::all(),
            'Doers' => $doers,
            'JobCount' => $jobCount,
            'JobHoldCount' => $jobHoldCount,
            'JobCmpleteCount' => $jobCmpleteCount,
            'JobProcessCount' => $jobProcessCount,
            'JobNotStartCount' => $jobNotStartCount
        ];

        return view('home')->with('Data', $data);
    }
}
