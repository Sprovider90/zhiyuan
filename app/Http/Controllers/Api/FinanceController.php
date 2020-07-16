<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersResources;
use App\Models\Orders;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Orders $orders , Request $request)
    {
        $orders = $orders->with('customs');
        $request->money     && $orders = $orders->whereBetween('money',explode('-',$request->money));
        $request->status    && $orders = $orders->where('order_status',$request->status);
        $request->name      && $orders = $orders->whereHas('customs',function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")->orWhere('company_addr','like','%'.$request->name.'%');
        });
        $request->time      && $orders = $orders->whereDate('created_at',$request->time);
        return new OrdersResources($orders->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $order)
    {
        return new OrdersResources($order->load(['devices','customs']));
    }


}
