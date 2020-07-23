<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;

use Spatie\QueryBuilder\QueryBuilder;




class MessageController extends Controller
{
	public function index(Request $request,Message $message)
    {
    	$query = Message::class;
        if(isset($request->type)&&!empty($request->type)){
        	$query =$message->where('type',$request['type']);
        }

        $messages = QueryBuilder::for($query)
            ->paginate();
        return MessageResource::collection($messages);
    }
    

}
