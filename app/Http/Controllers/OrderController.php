<?php

namespace App\Http\Controllers;

use App\BaoOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function all(Request $req)
    {
        $Data = BaoOrder::where("status", 'pending')->get();
        try {
            return json_encode($Data, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }

    public function getAllPrinted(Request $req)
    {
        $Data = BaoOrder::where("status", 'printed')->get();
        try {
            return json_encode($Data, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }

    public function get(Request $req)
    {
        $Data = array('Orders' => BaoOrder::where("id", $req->id)->first());
        try {
            return json_encode(BaoOrder::where("id", $req->id)->first(), JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }

    public function update(Request $req)
    {
        try {
            $order = BaoOrder::where('id', $req->id)->first();
            $order->status = 'printed';
            $order->save();

            return json_encode('sucess');
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }
}
