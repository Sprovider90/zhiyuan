<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrdersDevicesRequest;
use App\Http\Requests\Api\OrdersRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\OrdersResources;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrdersRequest $request,Orders $orders)
    {
        $data = $request->all();
        $orders = $orders->create($data);
        return response(new OrdersResources($orders),201);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $order)
    {
        return new OrdersResources($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrdersRequest $request,Orders $order)
    {
        $order->update($request->all());
        return response(new OrdersResources($order),201);
    }


    /**
     * Update Order And Create Devices.
     *
     * @param OrdersDevicesRequest $request
     * @param Orders $order
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function send(OrdersDevicesRequest $request,Orders $order){
        DB::transaction(function() use ($request, &$order){
            $request['send_goods_status'] = 2;
            $order->update($request->all());
            $order->devices()->delete();
            $order->devices()->createMany(json_decode($request->data,true));
        });
        return response(new OrdersResources($order->load('devices')),201);
    }

    /**
     * cancel order
     *
     * @param OrdersRequest $request
     * @param Orders $order
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function cancel(Orders $order)
    {
        if($order->order_status==1 && $order->send_goods_status == 1){
            $order->update(['order_status' => 4]);
            return response(new OrdersResources($order),201);
        }else{
            abort(400,'参数错误');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
