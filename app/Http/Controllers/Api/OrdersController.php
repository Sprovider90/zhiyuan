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
        /*$customers = $customers->with('contacts');
        $request->name && $customers = $customers->where(function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")->orWhere('company_addr','like','%'.$request->name.'%');
        });
        $request->contact && $customers = $customers->whereHas('contacts', function ($builder) use ($request,&$customers) {
            $builder->where('contact', 'like', "%{$request->contact}%");
        });
        return new CustomersResources($customers->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));*/
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
//        $data['order_number'] = $this->createOrderNumber();
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
        return new OrdersResources($order->load('devices'));
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


    public function send(OrdersDevicesRequest $request,Orders $order){
        DB::transaction(function() use ($request, &$order){
            $order->update($request->all());
            $order->devices()->createMany(json_decode($request->data,true));
        });
        return response(new OrdersResources($order->load('devices')),201);
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
