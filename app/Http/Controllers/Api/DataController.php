<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class DataController extends Controller
{

    protected function signUp(Request $request){

        $validator =FacadesValidator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            $hashedpassword=Hash::make($request->input("password"));

            $user=User::create([
                'email'=>$request->input("email"),
                'password'=>$hashedpassword,
                'type'=>$request->input("type"),
                'created_at'=>now(),
            ]);
            return(response()->json([
               "message"=>"successful registiration","user"=>$user,]
           ,201));
        }
    }

    protected function login(Request $request){
        $validator = FacadesValidator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        };

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->type === 'employer') {
                $employerInfo = Employer::where('user_id', $user->id)->first();
                if ($employerInfo) {
                    return response()->json(['message' => 'Login successful','user'=>$user,'info'=>$employerInfo],200);
                }
            } elseif ($user->type === 'candidate') {
                $candidateInfo = Candidate::where('user_id', $user->id)->first();
                if ($candidateInfo) {
                    return response()->json(['message' => 'Login successful','user'=>$user,'info'=>$candidateInfo],200);
                }
            }

        return response()->json(['message' => 'Login successful & no info was found', 'user' => $user,'info'=>null],200);
            
        } else {
            return response()->json(['error' => 'wrong password'], 401);
        }
}

    protected function candidate_registeration(Request $request){

        $validator =FacadesValidator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'overview' => 'nullable|string',
            'skills' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            if($request->file('resume')){
                $filePath = $request->file('resume')->store('resumes', 'public'); 
            }else{
                $filePath="";
            }

            $candidate=Candidate::create([
        'user_id'=> $request->input('user_id'),
        'firstname' => $request->input('firstname'),
        'lastname' => $request->input('lastname'),
        'title' => $request->input('title'),
        'location' => $request->input('location'),
        'overview' => $request->input('overview'),
        'resume' => $filePath,
        'skills' => $request->input('skills'),
        'created_at'=>now(),
        ]);
        }
        return response()->json(['message' => 'Candidate created successfully',"candidate"=>$candidate], 201);
    }
    protected function candidate_edit(Request $request){

        $validator =FacadesValidator::make($request->all(), [
            'id' => 'required|exists:candidates,id',
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'title' => 'string|max:255',
            'location' => 'nullable|string|max:255',
            'overview' => 'nullable|string',
            'skills' => 'nullable|string|max:255',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            $id=$request->input('id');
            $candidate = Candidate::where('id', $id)->first();

            if($request->file('resume')){
                $filePath = $request->file('resume')->store('resumes', 'public'); 
            }else{
                $filePath="";
            }

            $candidate->update([
        'user_id'=> $request->input('user_id'),
        'firstname' => $request->input('firstname'),
        'lastname' => $request->input('lastname'),
        'title' => $request->input('title'),
        'location' => $request->input('location'),
        'overview' => $request->input('overview'),
        'resume' => $filePath,
        'skills' => $request->input('skills'),
        'updated_at'=>now(),
        ]);
        }
        return response()->json(['message' => 'Candidate info was edited successfully',"candidate"=>$candidate], 201);
    }

    protected function employer_registeration(Request $request){
        
        $validator =FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,jpg,png,svg,webp|max:2048',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            }else{
                $logoPath="";
            }
        $employer=Employer::create([
        'user_id'=> $request->input('user_id'),
        'name' => $request->input('name'),
        'logo' => $logoPath,
        'location' => $request->input('location'),
        'description' => $request->input('description'),
        'created_at'=>now(),
        ]);
        }
        return response()->json(['message' => 'Employer created successfully',"employer"=>$employer], 201);
}


    protected function employer_edit(Request $request){
        
        $validator =FacadesValidator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,jpg,png,svg,webp|max:2048',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'id' => 'required|exists:employers,id',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }else{
            $id=$request->input('id');
            $employer = Employer::where('id', $id)->first();

            if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            }else{
                $logoPath="";
            }

        $employer->update([
        'name' => $request->input('name'),
        'logo' => $logoPath,
        'location' => $request->input('location'),
        'description' => $request->input('description'),
        'updated_at'=>now(),
        ]);
        }
        return response()->json(['message' => 'Employer edited successfully',"employer"=>$employer], 201);
    }
}
