<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;

use Spatie\QueryBuilder\QueryBuilder;




class MessageController extends Controller
{
    public function noread(Request $request,Message $message)
    {

        //$this->authorize('list',$message);

        $messages =$message->where('user_id',$request->user()->id)->where("is_read",0)->count();

        return response()->json([
            'noread'       => $messages,
        ]);

    }

	public function index(Request $request,Message $message)
    {

        //$this->authorize('list',$message);

    	$query =$message->where('user_id',$request->user()->id);
        $query->where('user_id',$request->user()->id)->update(['is_read' => 1]);
        if(isset($request->type)&&!empty($request->type)){
        	$query =$query->where('type',$request['type']);
        }


        $messages = QueryBuilder::for($query)
            ->paginate($request->pageSize ?? $request->pageSize);

        return MessageResource::collection($messages);
    }
    public function destroy(Message $message)
    {
        $message->delete();
        return response(null, 204);
    }

}
