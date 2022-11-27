<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function all(Request $req)
    {
        $Data = array('Orders' => Orders::all());
        try {
            return json_encode($Data, JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }

    public function update(Request $req)
    {
        try {
            $order = Orders::where('id', $req->id)->first();
            $order->status = 'complete';
            $order->save();

            return json_encode('sucess');
        } catch (\Throwable $th) {
            return json_encode('err');
        }
    }
}
