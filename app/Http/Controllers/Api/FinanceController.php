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
        //订单总金额
        $order_money_count = Orders::whereNotIn('order_status',[1,2,3])->whereBetween('created_at',$date)->sum('money');
        //已收款
        $receive_money_count  = Orders::whereNotIn('order_status',[1])->whereBetween('created_at',$date)->sum('money');
        $ok_order = Orders::whereNotIn('order_status',[2])->whereBetween('created_at',$date)->get();
        foreach ($ok_order as $k => $v){
            $receive_money_count+=FinanceLog::where('order_id',$v->id)->sum('money');
        }
        //待收款
        $wait_money_count = $order_money_count - $receive_money_count;
        //已退款
        $exit_money_count = Orders::whereNotIn('order_status',[4])->whereBetween('created_at',$date)->sum('money');
        return response()->json([
            'order_money_count'     => $order_money_count,
            'receive_money_count'   => $receive_money_count,
            'wait_money_count'      => $wait_money_count,
            'exit_money_count'      => $exit_money_count,
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
        $request->money     && $finance = $finance->whereBetween('money',explode('-',$request->money));
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
        return new FinanceResource($finance->load(['devices','customs','logs','logs.user']));
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
            $order_status = 3;
            if($request->type == 2){
                //退款
                $order_status = 4;
            }else{
                //收款
                $order = Orders::find($finance->id);
                $order_money_count = FinanceLog::where('order_id',$finance->id)->sum('money');
                $order_money_count = $order_money_count + $request->money;
                if($order_money_count>= $order->money){
                    //修改收款状态 为2
                    $order_status = 2;
                }
            }
            $finance->update(['order_status' => $order_status]);
            $finance->logs()->create($request->all());
        });
        return response(new FinanceResource($finance->load(['logs','logs.user'])),201);
    }


}
