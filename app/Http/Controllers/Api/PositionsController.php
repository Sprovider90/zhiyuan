<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PositionsRequest;
use App\Http\Resources\OrdersResources;
use App\Http\Resources\PositionsResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionsController extends Controller
{
    //
    public function store(PositionsRequest $request,Position $positions)
    {
        $positions = $positions->create($request->all());
        return response(new PositionsResource($positions));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionsRequest $request,Position $position)
    {
        $position->update($request->all());
        return response(new PositionsResource($position));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Position::where('id',$id)->delete();
        return response(null, 204);
    }

    public function status(Request $request,Position $position){
        $position->update(['status' => $request->status]);
        return response(new PositionsResource($position),201);
    }
}
