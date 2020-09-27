<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrdersDevicesRequest;
use App\Http\Requests\Api\OrdersRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\OrdersResources;
use App\Models\Device;
use App\Models\Finance;
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
        $where = [];
        $request->user()->customer_id && $where[] = ['cid',$request->user()->customer_id];
        //订单总金额
        $order_money_count = Orders::where($where)->whereNotIn('order_status',[5])->whereBetween('created_at',$date)->sum('money');
        //订单总数
        $order_count = Orders::where($where)->whereNotIn('order_status',[5])->whereBetween('created_at',$date)->count();
        //20200926需求变更
        //已付款订单
        $order_pay_count = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',2)->count();
        //部分付款订单
        $order_section_pay_count = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',3)->count();
        //待付款订单
        $order_wait_count = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',1)->count();
        //已付款（实收的金额）
        $order_payment_money = 0 ;
        $order_payment = Orders::where($where)->whereBetween('created_at',$date)->whereIn('order_status',[2,3,6])->get();
        foreach ($order_payment as $k => $v){
            switch ($v->order_status){
                case 2:
                    $order_payment_money += $v->money;
                    break;
                case 3:
                    $money = FinanceLog::where('order_id',$v->id)->where("type",1)->sum('money');
                    $order_payment_money += $money;
                    break;
                case 6:
                    $log = FinanceLog::where("order_id",$v->id)->groupBy('type')->select(['type',DB::raw('SUM(money) as money')])->get();
                    foreach ($log as $k1 => $v1){
                        if($v1->type == 1){
                            $order_payment_money += $v1->money;
                        }else{
                            $order_payment_money -= $v1->money;
                        }
                    }
                    break;
            }
        }
        //待付款
        $order_wait_money = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',1)->sum('money');
        //已退款
        DB::connection()->enableQueryLog();
        $order_refund = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',[4,6])->get(['id']);
        $order_refund_money = FinanceLog::whereIn('order_id',$order_refund)->where('type',2)->sum('money');
        var_dump(DB::getQueryLog());exit;
        return response()->json([
            //订单总数
            'order_count'                   => $order_count,
            //已付款订单
            'order_pay_count'               => $order_pay_count,
            //部分付款订单
            'order_section_pay_count'       => $order_section_pay_count,
            //待付款订单
            'order_wait_count'              => $order_wait_count,
            //订单总金额
            'order_money_count'             => sprintf("%.2f",$order_money_count),
            //已付款（实收的金额）
            'order_payment_money'           => sprintf("%.2f",$order_payment_money),
            //待付款
            'order_wait_money'              => sprintf("%.2f",$order_wait_money),
            //已退款
            'order_refund_money'            => sprintf("%.2f",$order_refund_money),
        ]);


        /*$date = $this->returnDate($request->type ?? 2);
        $where = [];
        $request->user()->customer_id && $where[] = ['cid',$request->user()->customer_id];

        //待付款订单数
        $order_no_pay_count = Orders::where($where)->whereBetween('created_at',$date)->where('order_status',1)->count();

        //已付订单总金额
        $order_pay_money_count = 0;
        $order_pay = Orders::where($where)->whereBetween('created_at',$date)->whereIn('order_status',[2,3,4,6])->get();
        if($order_pay){
            foreach ($order_pay as $k => $v){
                if($v->order_status == 2){
                    $order_pay_money_count += $v->money;
                }else{
                    $log = FinanceLog::where("order_id",$v->id)->get();
                    foreach ($log as $k1 => $v1){
                        if($v1->type == 1){
                            $order_pay_money_count +=  $v1->money;
                        }else{
                            $order_pay_money_count -=  $v1->money;
                        }
                    }

                }
            }
        }
        //待付订单总金额
        $order_no_pay_money_count = 0;
        $order_no_pay_money = Orders::where($where)->whereBetween('created_at',$date)->whereIN('order_status',[1,3])->whereBetween('created_at',$date)->get();
        if($order_no_pay_money){
            foreach ($order_no_pay_money as $k => $v){
                $order_no_pay_money_count  +=  $v->money;
                if($v->order_status == 3){
                    $log = FinanceLog::where("order_id",$v->id)->get();
                    foreach ($log as $k1 => $v1){
                        $order_no_pay_money_count -= $v1->money;
                    }
                }
            }
        }
        return response()->json([
                'order_count'           => $order_count,
                'order_pay_count'       => $order_pay_count,
                'order_no_pay_count'    => $order_no_pay_count,
                'order_money_count'         => sprintf("%.2f",$order_money_count),
                'order_pay_money_count'     => sprintf("%.2f",$order_pay_money_count),
                'order_no_pay_money_count'  => sprintf("%.2f",$order_no_pay_money_count),
        ]);*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Orders $orders , Request $request)
    {
        $orders = $orders->with('customs');
        $request->user()->customer_id && $orders = $orders->where('cid',$request->user()->customer_id);
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
            $data = json_decode($request->data,true);
            $order->devices()->createMany(json_decode($request->data,true));
            //修改设备表中状态为 已出库 同时绑定客户关系 8-31沟通
            foreach ($data as $k => $v){
                Device::where('id',$v['device_id'])->update(['store_status' => 2,'customer_id' => $order->cid ]);
            }
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
