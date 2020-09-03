<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FinanceLogRequest;
use App\Http\Requests\Api\FinanceRequest;
use App\Http\Resources\FinanceLogResource;
use App\Http\Resources\FinanceResource;
use App\Http\Resources\OrdersResources;
use App\Models\Finance;
use App\Models\FinanceLog;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    //

    public function count(Request $request){
        $date = $this->returnDate($request->type ?? 2);
        $where = [];
        $request->user()->customer_id && $where[] = ['cid',$request->user()->customer_id];
        //订单总金额
        $order_money_count = Finance::where($where)->whereBetween('created_at',$date)->sum('money');
        //已收款
        $receive_money_count = 0;
        $order_pay = Finance::where($where)->whereBetween('created_at',$date)->whereIn('order_status',[2,3,4])->whereBetween('created_at',$date)->get();
        if($order_pay){
            foreach ($order_pay as $k => $v){
                if($v->order_status == 2){
                    $receive_money_count += $v->money;
                }else{
                    $log = FinanceLog::where("order_id",$v->id)->get();
                    foreach ($log as $k1 => $v1){
                        if($v1->type == 1){
                            $receive_money_count+= $v1->money;
                        }else{
                            $receive_money_count-= $v1->money;
                        }
                    }
                }
            }
        }
        //待收款
        $wait_money_count = 0;
        $order_no_pay_money = Finance::where($where)->whereBetween('created_at',$date)->whereIN('order_status',[1,3])->whereBetween('created_at',$date)->get();
        if($order_no_pay_money){
            foreach ($order_no_pay_money as $k => $v){
                $wait_money_count  +=  $v->money;
                if($v->order_status == 3){
                    $log = FinanceLog::where("order_id",$v->id)->get();
                    foreach ($log as $k1 => $v1){
                        $wait_money_count -= $v1->money;
                    }
                }
            }
        }
        //已退款
        $exit_money_count = 0;
        $exit_money_order = Finance::where($where)->whereBetween('created_at',$date)->whereIn('order_status',[4])->whereBetween('created_at',$date)->get();
        foreach ($exit_money_order as $k => $v){
                $log = FinanceLog::where("order_id",$v->id)->where('type',2)->get();
                foreach ($log as $k1 => $v1){
                    $exit_money_count += $v1->money;
                }
        }
        return response()->json([
            'order_money_count'     => sprintf("%.2f",$order_money_count),
            'receive_money_count'   => sprintf("%.2f",$receive_money_count),
            'wait_money_count'      => sprintf("%.2f",$wait_money_count),
            'exit_money_count'      => sprintf("%.2f",$exit_money_count),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Finance $finance , Request $request)
    {
        $finance = $finance->with('customs');
        $request->user()->customer_id && $finance = $finance->where('cid',$request->user()->customer_id);
        if($request->money){
            $arr = explode('-',$request->money);
            if(count($arr) == 1){
                $finance = $finance->where('money','>',$arr[0]);
            }else if(count($arr) == 2){
                $finance = $finance->whereBetween('money',explode('-',$request->money));
            }
        }

        $request->status    && $finance = $finance->where('order_status',$request->status);
        $request->name      && $finance = $finance->whereHas('customs',function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")->orWhere('company_addr','like','%'.$request->name.'%');
        });
        $request->time      && $finance = $finance->whereDate('created_at',$request->time);
        return new FinanceResource($finance->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Finance $finance )
    {
        //已收
        $money = FinanceLog::where('order_id',$finance->id)->where('type',1)->sum('money');
        //已退
        $money1 = FinanceLog::where('order_id',$finance->id)->where('type',2)->sum('money');
        $money3 = $finance->money - $money + $money1;
        $money4 = $money - $money1;
        $finance->wait_put_moeny = $money3;
        $finance->wait_back_meony = $money4;
        return new FinanceResource($finance->load(['devices','customs']));
    }


    /**
     * Update Order And Create Devices.
     *
     * @param FinanceRequest $request
     * @param Finance $finance
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(FinanceRequest $request,Finance $finance){
        DB::transaction(function() use ($request, &$finance){
            if($request->type){
                if($request->type == 1){
                    //收款
                    $order = Orders::find($finance->id);
                    //已收
                    $order_money_count = FinanceLog::where('order_id',$finance->id)->where('type',1)->sum('money');
                    $money = $order->money - $order_money_count;
                    if($money > $request->money){
                        $order_status = 3;
                    }else if($money == $request->money){
                        $order_status = 2;
                    }else if($money < $request->money  ){
                        return $this->errorResponse('400','收款金额大于代收款金额');
                    }
                }else if($request->type == 2){
                    //退款
                    $order = Orders::find($finance->id);
                    //已收
                    $order_money_count = FinanceLog::where('order_id',$finance->id)->where('type',1)->sum('money');
                    if($order_money_count < $request->money){
                        return $this->errorResponse('400','退款金额必须小于已收款金额');
                    }else{
                        $order_status = 4;
                    }
                }
                $finance->update(['order_status' => $order_status]);
                $finance->logs()->create($request->all());
            }else{
                return $this->errorResponse('400','type 参数错误');

            }
        });
        return response(new FinanceResource($finance->load(['logs','logs.user'])),201);
    }


    public function financeLog($orderId,FinanceLog $financeLog,Request $request){
        $financeLog = $financeLog->with('user')
            ->where('order_id',$orderId)
            ->orderBy('id','desc');
        return new OrdersResources($financeLog->paginate($request->pageSize ?? $request->pageSize));
    }

}
