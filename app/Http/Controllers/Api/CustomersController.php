<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Resources\CustomersResources;
use App\Http\Resources\ProjectsResources;
use App\Models\Customers;
use App\Models\CustomersContacts;
use App\Models\Projects;
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
        $customers = $customers->with('contacts');
        $request->name && $customers = $customers->where(function($query) use ($request){
            $query->where('company_name','like',"%{$request->name}%")->orWhere('company_addr','like','%'.$request->name.'%');
        });
        $request->contact && $customers = $customers->whereHas('contacts', function ($builder) use ($request,&$customers) {
            $builder->where('contact', 'like', "%{$request->contact}%");
        });
        return new CustomersResources($customers->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize));
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
        $customer  = $customer->load(['contacts','projects' =>function($query) use ($request){
            $query->orderBy('id','desc')
                ->paginate(
                    $request->pageSize ?? $request->pageSize,
                    '*',
                    'page',
                    $request->page ?? $request->page
                );
        }]);
        return new CustomersResources($customer);
    }

    /*public function show($id,Projects $projcets)
    {
        $projcets = $projcets->with(['customs' => function($query){
            $query->with('contacts');
        }])->where('customer_id',$id)->orderBy('id','desc')->paginate(15);
        return response(new ProjectsResources($projcets));
    }*/


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
