<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomersRequest;
use App\Http\Resources\CustomersResources;
use App\Models\Customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customers $customers , Request $request)
    {
        if($request->name){
            $customers = $customers->where('company_name','like','%'.$request->name.'%')->orWhere('company_addr','like','%'.$request->name.'%');
        }
        if($request->contact){
            $customers = $customers->where('contact','like','%'.$request->contact.'%');
        }
        return new CustomersResources($customers->paginate($request->pageSize ?? $request->pageSize));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomersRequest $request)
    {
        $customer = Customers::create($request->all());
        return new CustomersResources($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customers $customer)
    {
        return new CustomersResources($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customers $customer)
    {
        return new CustomersResources($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Customers $customer,CustomersRequest $request)
    {
        $customer->update($request->all());
        return new CustomersResources($customer);
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
