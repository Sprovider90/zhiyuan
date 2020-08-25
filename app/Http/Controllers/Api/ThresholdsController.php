<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dictories;
use Illuminate\Http\Request;
use App\Models\Thresholds;
use App\Http\Resources\ThresholdsResource;
use App\Http\Requests\Api\ThresholdsRequest;
class ThresholdsController extends Controller
{
    public function index(Request $request, Thresholds $Thresholds)
    {
        $query = $Thresholds->query();

        if ($name = $request->name) {
            $query->where('name', $name);
        }

        $Thresholds = $query->paginate($request->pageSize ?? $request->pageSize);

        return ThresholdsResource::collection($Thresholds);
    }
    public function store(ThresholdsRequest $request, Thresholds $Thresholds)
    {
        $Thresholds->fill($request->all());
        $Thresholds->save();

        return new ThresholdsResource($Thresholds);
    }
    public function update(ThresholdsRequest $request, $id)
    {
        $Thresholds = Thresholds::findOrFail($id);
        $attributes = $request->only(['thresholdinfo',"status"]);
        try{
            $Thresholds->update($attributes);
        }catch(\Exception $e){
            abort(400, '内部错误');
        }

        return new ThresholdsResource($Thresholds);
    }
    public function show(Thresholds $threshold)
    {
        return new ThresholdsResource($threshold);
    }
}
