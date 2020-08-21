<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionsResource;
use App\Http\Resources\ProjectsResources;
use App\Models\ProjectsPositions;
use Illuminate\Http\Request;

class DataController extends Controller
{
    //
    public function index(Request $request,ProjectsPositions $position){
        $position = $position->with(['project']);
        $request->keyword && $position
            ->orWhere('name','like',"%{$request->keyword}%")
            ->orWhereHas('project',function ($query) use ($request) {
                return $query->where('name','like',"%{$request->keyword}%");
            });
        $position = $position->orderBy('id','desc')->paginate($request->pageSize ?? $request->pageSize);
        return response(new PositionsResource($position));
    }
}
