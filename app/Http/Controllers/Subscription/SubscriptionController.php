<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Subscription_plan;
use App\Models\Subscriber;
use App\Http\Resources\subscriptionResource;
use Illuminate\Http\Request;
use Validator;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $subs = Subscription_plan::where('status',1)->get();
        return response([ 'subs' => subscriptionResource::collection($subs), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'validity' => 'required',
            'status' => 'required',
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }

        $subs = Subscription_plan::create($data);

        return response([ 'subs' => new subscriptionResource($subs), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription_plan  $subscription_plan
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription_plan $subscription_plan)
    {
        //
        return response([ 'subs' => new subscriptionResource($subscription_plan), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription_plan  $subscription_plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription_plan $subscription_plan)
    {
        //
        $subscription_plan->update($request->all());

        return response([ 'subs' => new subscriptionResource($subscription_plan), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription_plan  $subscription_plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription_plan $subscription_plan)
    {
        //
        $subscription_plan->delete();

        return response(['message' => 'Deleted']);
    }

    public function cancleSubscription(Request $request)
    {
        //
        $data = $request->all();
        $rules = [
            'user_id' => 'required',
            'plan_id' => 'required'
        ];

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['status' => 'false', 'error' => $error->errors()->all()], 200);
        }
        
        // $subscription_det=\App\Models\Subscription::where(['user_id'=>$data['user_id'],'subscription_plan_id'=>$data['plan_id']])->first();
        $subscription_can=Subscriber::where(['user_id'=>$data['user_id'],'subscription_id'=>$data['plan_id']])->update(['auto_renew'=>0]);

        return response(['message' => 'Your plan is Successfully Cancelled !! However, You can enjoy existing plan benefits.']);
    }
    
}
