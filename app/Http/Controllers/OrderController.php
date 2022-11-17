<?php

namespace App\Http\Controllers;

use App\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function update(Request $req)
    {
        $order = Orders::where('id', $req->id)->first();
        $order->status = 'complete';
        $order->save();
    }
}
