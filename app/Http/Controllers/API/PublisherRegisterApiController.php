<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherRegistration;
use Validator;
use Illuminate\Support\Facades\File;
class PublisherRegisterApiController extends Controller
{
    public function index()
    {
        $publisher = PublisherRegistration::all();
        return response()->json(['success'=>true,'data'=>$publisher],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $publisher = new PublisherRegistration;
        $rules = [
            'company_name'=>'required',
            'address'=>'required',
            'city'=>'required',
            'postal_code'=>'required',
            'upload_address_proof'=>'required',
            'pan_card'=>'required',
            'aadhar_card'=>'required',
            'gst_or_tin_card'=>'required|nullable',
            'first_name'=>'required',
            'last_name'=>'required',
            'username'=>'required',
            'password'=>'required',
            'email'=>'required|unique:publisher_registrations,email',
            'select_question'=>'required',
            'security_answer'=>'required',
            'check_box'=>'required',

        ];

        $validator = \Validator::make($input,$rules);
        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=>$validator->errors()->all()],200);
        }

        $publisher = new PublisherRegistration;
        $publisher->company_name    = $input['company_name']; 
        $publisher->address         = $input['address']; 
        $publisher->city            = $input['city'];
        $publisher->postal_code     = $input['postal_code'];
        $publisher->first_name      = $input['first_name'];
        $publisher->last_name       = $input['last_name'];
        $publisher->username        = $input['username'];
        $publisher->password        = $input['password'];
        $publisher->email           = $input['email'];
        $publisher->select_question = $input['select_question'];
        $publisher->security_answer = $input['security_answer'];
        $publisher->check_box       = $input['check_box'];

        if($request->hasFile('upload_address_proof')) {
            $file = $request->file('upload_address_proof');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['upload_address_proof']=$fileName;
            $publisher->upload_address_proof = $input['upload_address_proof'];
        }
        if($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['pan_card']=$fileName;
            $publisher->pan_card = $input['pan_card'];
        }
        if($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['aadhar_card']=$fileName;
            $publisher->aadhar_card = $input['aadhar_card'];
        }
        if($request->hasFile('gst_or_tin_card')) {
            $file = $request->file('gst_or_tin_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['gst_or_tin_card']=$fileName;
            $publisher->gst_or_tin_card = $input['gst_or_tin_card'];
        }

        $publisher->save();
        return response()->json(['status'=>true,'msg'=>'publisher registered successfully !!'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publisher_edit = PublisherRegistration::where('id',$id)->get();
        return response()->json(['status'=>true,'data'=>$publisher_edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $publisher = new PublisherRegistration;
        $rules = [
            'company_name'=>'required',
            'address'=>'required',
            'city'=>'required',
            'postal_code'=>'required',
            'upload_address_proof'=>'required',
            'pan_card'=>'required',
            'aadhar_card'=>'required',
            'gst_or_tin_card'=>'required|nullable',
            'first_name'=>'required',
            'last_name'=>'required',
            'username'=>'required',
            'password'=>'required',
            'email'=>"required|email|max:128|unique:publisher_registrations,email,".$id, 
            'select_question'=>'required',
            'security_answer'=>'required',
            'check_box'=>'required',

        ];

        $validator = \Validator::make($input,$rules);
        if($validator->fails()){
            return response()->json(['status'=>false,'errors'=>$validator->errors()->all()],200);
        }

        $publisher =  PublisherRegistration::where('id',$id)->first();
        $publisher->company_name    = $input['company_name']; 
        $publisher->address         = $input['address']; 
        $publisher->city            = $input['city'];
        $publisher->postal_code     = $input['postal_code'];
        $publisher->first_name      = $input['first_name'];
        $publisher->last_name       = $input['last_name'];
        $publisher->username        = $input['username'];
        $publisher->password        = $input['password'];
        $publisher->email           = $input['email'];
        $publisher->select_question = $input['select_question'];
        $publisher->security_answer = $input['security_answer'];
        $publisher->check_box       = $input['check_box'];

        if (File::exists(public_path('uploads/'.$input['company_name'].'/document'))) {
                
                unlink($image_path);
        }
    
        if($request->hasFile('upload_address_proof')) {
            $file = $request->file('upload_address_proof');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['upload_address_proof']=$fileName;
            $publisher->upload_address_proof = $input['upload_address_proof'];
        }
        if($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['pan_card']=$fileName;
            $publisher->pan_card = $input['pan_card'];
        }
        if($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['aadhar_card']=$fileName;
            $publisher->aadhar_card = $input['aadhar_card'];
        }
        if($request->hasFile('gst_or_tin_card')) {
            $file = $request->file('gst_or_tin_card');
            $fileName= time().$file->getClientOriginalName();
            $fileName=str_replace("","_",$fileName);
            $file->move(public_path('uploads/'.$input['company_name'].'/document'),$fileName);
            $input['gst_or_tin_card']=$fileName;
            $publisher->gst_or_tin_card = $input['gst_or_tin_card'];
        }

        $publisher->save();
        return response()->json(['status'=>true,'msg'=>'publisher updated successfully !!'],200);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publisher_delete = PublisherRegistration::where('id',$id)->first();
        $publisher_delete->delete();

        return response()->json(['status'=>true,'msg'=>'publisher deleted successfully!!.']);
    }

}
