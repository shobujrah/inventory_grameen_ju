<?php

namespace App\Http\Controllers\Api;

use App\Models\Requisition;
use Illuminate\Http\Request;
use App\Models\RequisitionItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RequisitionapiController extends Controller
{
    public function list (){
        $requisitions=Requisition::all();
        return response()->json([
            'success'=>true,
            'data'=>$requisitions,
            'message'=>'All requisition list',
            'status_code'=>200
        ]);

    }



    public function create(Request $request){

        $request->validate([
            'branch_name' => 'required',
            'project_name' => 'required',
            'date_from' => 'required',
            'items.*.name' => 'required',
            'items.*.description' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|integer|min:1',
        ]);
    
        $requisition = Requisition::create([
            'branch_name' => $request->branch_name,
            'project_name' => $request->project_name,
            'date_from' => $request->date_from,
        ]);
    
        foreach ($request->items as $item) {
            $totalPrice = $item['price'] * $item['amount']; 
            $RequisitionItem = RequisitionItem::create([
                'requisition_id' => $requisition->id,
                'product_description' => $item['description'],
                'single_product_name' => $item['name'],
                'price' => $item['price'],
                'demand_amount' => $item['amount'],
                'total_price' => $totalPrice, 
                'stock_level' => $item['stock'],
                'purchase_authorization_amount' => $item['amount'],
                'comment' => $item['comment'],
            ]);
        }

        return response()->json([
            'success'=>true,
            'data'=>$requisition,
            'data'=>$totalPrice,
            'data'=>$RequisitionItem,
            'message'=>'Requisition created successfullly',
            'status_code'=>200

        ]);
    }


    public function view($id) {
        $requisitionheading = Requisition::find($id);
        $requisitionlist = RequisitionItem::where('requisition_id', $id)->get();
        $requisitions=Requisition::all();
        $requisitionss=RequisitionItem::all();
    
        if (!$requisitionheading || $requisitionlist->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Requisition not found',
                'status_code' => 404
            ]);
        }
    
        return response()->json([
            'success' => true,
            'requisition_heading' => $requisitionheading,
            'requisition_items' => $requisitionlist,
            'requisitions' => $requisitions,
            'requisitionss' => $requisitionss,
            'message' => 'Requisition details retrieved successfully',
            'status_code' => 200
        ]);
    }
    


    public function login(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',

        ]);

        if($validate->fails()){
            return $this->responseWithError($validate->getMessageBag());
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        if($token)
        {
            return $this->responseWithSuccess($token,'Login Success');
        }

        return $this->responseWithError('Invalid User.');
    }


}

