<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\ProjectsResources;
use App\Models\Customers;
use App\Models\CustomersContacts;
use App\Models\Projects;
use App\Models\ProjectsPositions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customers $customers , Request $request)
    {
        $customers = $customers->with(['contacts','logosFile']);
        $request->name && $customers = $customers->where(function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")->orWhere('company_addr','like','%'.$request->name.'%');
        });
        $request->contact && $customers = $customers->whereHas('contacts', function ($builder) use ($request,&$customers) {
            $builder->where('contact', 'like', "%{$request->contact}%");
        });
        $customers = $customers->orderBy('id','desc')
            ->paginate($request->pageSize ?? $request->pageSize);
        foreach ($customers as $k => $v){
            $v->project_count = Projects::where('customer_id',$v->id)->count();
            //查询客户所有项目
            $proList = Projects::where('customer_id',$v->id)->get(['id']);
            $v->position_count = ProjectsPositions::whereIn('project_id',$proList)->count();
        }
        return new CustomersResources($customers);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomersRequest $request,Customers $customers)
    {
        DB::transaction(function() use ($request, &$customers){
            $customers = $customers->create($request->all());
            $customers->contacts()->createMany(json_decode($request->contact,true));
        });
        return response(new CustomersResources($customers->load('contacts')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customers $customer,Request $request)
    {
        $customer  = $customer->load(['contacts']);
        return new CustomersResources($customer);
    }

    /**
     * 客户详情项目列表接口
     *
     * @param $customer
     * @param Projects $projects
     * @param Request $request
     * @return ProjectsResources
     */
    public function projects($customer,Projects $projects,Request $request){
        $projects = $projects->where('customer_id',$customer);
        return new ProjectsResources($projects->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customers $customer)
    {
        return new CustomersResources($customer->load('contacts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomersRequest $request,Customers $customer)
    {
        DB::transaction(function() use ($request, &$customer){
            $customer->update($request->all());
            $customer->contacts()->delete();
            $customer->contacts()->createMany(json_decode($request->contact,true));
        });
        return response(new CustomersResources($customer->load('contacts')),201);
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
