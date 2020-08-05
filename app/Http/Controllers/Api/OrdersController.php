<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrdersDevicesRequest;
use App\Http\Requests\Api\OrdersRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\OrdersResources;
use App\Models\FinanceLog;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{

    /**
     * 统计订单数
     * $type  1本周 2本月 3今年 默认2
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request){
        $date = $this->returnDate($request->type ?? 2);
        //订单总数
        $order_count = Orders::whereNotIn('order_status',[1,2,3])->whereBetween('created_at',$date)->count();
        //已付款订单数
        $order_pay_count = Orders::where('order_status',2)->whereBetween('created_at',$date)->count();
        //待付款订单数
        $order_no_pay_count = $order_count - $order_pay_count;
        //订单总金额
        $order_money_count = Orders::whereNotIn('order_status',[1,2,3])->whereBetween('created_at',$date)->sum('money');
        //已付订单总金额
        $order_pay_money_count = Orders::where('order_status',2)->whereBetween('created_at',$date)->sum('money');
        //待付订单总金额
        $order_no_pay_money_count = $order_money_count - $order_pay_money_count;

        return response()->json([
                'order_count'           => $order_count,
                'order_pay_count'       => $order_pay_count,
                'order_no_pay_count'    => $order_no_pay_count,
                'order_money_count'         => $order_money_count,
                'order_pay_money_count'     => $order_pay_money_count,
                'order_no_pay_money_count'  => $order_no_pay_money_count,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Orders $orders , Request $request)
    {
        $orders = $orders->with('customs');
        if($request->money){
            $arr = explode('-',$request->money);
            if(count($arr) == 1){
                $orders = $orders->where('money','>',$arr[0]);
            }else if(count($arr) == 2){
                $orders = $orders->whereBetween('money',explode('-',$request->money));
            }
        }
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
            $order->update(['order_status' => 5]);
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


    public function financeLog($orderId,FinanceLog $financeLog,Request $request){
        $financeLog = $financeLog->with('user')
            ->where('order_id',$orderId)
            ->orderBy('id','desc');
        return new OrdersResources($financeLog->paginate($request->pageSize ?? $request->pageSize));
    }



}
