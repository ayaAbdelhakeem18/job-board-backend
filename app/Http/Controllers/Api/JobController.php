<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationSubmitted;
use App\Models\Application;
use App\Models\Category;
use App\Models\Employer;
use App\Models\FeaturedJob;
use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    function post_job(Request $request){
        $validator=Validator::make($request->all(),[
            'employer_id' => 'required|exists:employers,id',
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:50|min:5',
            'salary' => 'required|string|max:20',
            'location' => 'required|string|min:5',
            'requirements' => 'required|string|min:250',
            'description' => 'required|string|min:200',
            'type' => 'required|in:full time,part time,freelance',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $job = new Job();
        $job->employer_id = $request->input('employer_id');
        $job->category_id = $request->input('category_id');
        $job->title = $request->input('title');
        $job->salary = $request->input('salary');
        $job->location = $request->input('location');
        $job->requirements = $request->input('requirements');
        $job->description = $request->input('description');
        $job->type = $request->input('type');
        $job->created_at = now();
        $job->updated_at = null;

        $job->save();

        DB::table('employer_activities')->insert([
            'employer_id' => $request->input('employer_id'),
            'job_id' => $job->id,
        ]);
         
        return response()->json(['message' => 'Job posted successfully','id'=>$job->id]);
    }

    function job_list(){

        $jobs = Job::all();
        $jobList = [];
        foreach ($jobs as $job) {
            $employerInfo = Employer::find($job->employer_id);
            if ($employerInfo) {
                $jobList[] = [
                    'job' => $job->toArray(),
                    'employer_info' => $employerInfo->toArray(),
                ];
            }
        }
        return response()->json($jobList);
    }

    function get_employers(){

    $employerNames = Employer::all();
    return response()->json($employerNames);

    }

    function apply(Request $request){

    $validator=Validator::make($request->all(),[
    'job_id'=>'required|exists:jobs,id',
    'candidate_id'=>'required|exists:candidates,id',
    'name'=>'required|min:11|max:50',
    'email'=>'required|email',
    'cover_letter'=>'required|min:50|max:500',
    'resume'=>'required',
    ]);
    if($validator->fails()){
        return response()->json(['error' => $validator->errors()], 422);
    }else{
        if($request->file('resume')){
            $filePath = $request->file('resume')->store('resumes', 'public'); 
        }else{
            $filePath=$request->input('resume');
        }

    $application=Application::create([
    'job_id'=> $request->input('job_id'),
    'candidate_id' => $request->input('candidate_id'),
    'name' => $request->input('name'),
    'email' => $request->input('email'),
    'cover_letter' => $request->input('cover_letter'),
    'resume' => $filePath,
    'created_at'=>now(),
    'updated_at'=>null,
    ]);

    $employerId = DB::table('jobs')->where('id', $request->input('job_id'))->value('employer_id');

    $userId = DB::table('employers')->where('id', $employerId)->value('user_id');
    $employerEmail = DB::table('users')->where('id', $userId)->value('email');
    
//Email to the employer

    Mail::to($employerEmail)->send(new ApplicationSubmitted($application));

    DB::table('candidate_activity')->insert([
        'candidate_id' => $request->input('candidate_id'),
        'application_id' => $application->id,
    ]);    
}


    return response()->json(['message' => 'application submitted successfully',"application"=>$application], 201);
    }

    function category_list(){
        $categories = Category::all();
        $categoryList = $categories->map(function ($category) {
            return [
                'id'    => $category->id,
                'title' => $category->title,
            ];
        });

        return response()->json($categoryList);
    }

    function candi_activity($id){

        // Get application data
        $data = Application::where('candidate_id', $id)
            ->select('created_at', 'status', 'job_id')
            ->get();
            $data = $data->map(function ($item) {
            $job = Job::find($item->job_id);
            if ($job) {
                $employer = Employer::find($job->employer_id);
                $item->employer_name = $employer->name ?? null;
            } else {
                $item->employer_name = null;
            }
            return $item;
        });
    
        return response()->json(['data' => $data], 200);
    }

    function employer_activity($id){

        $jobsData = Job::where('employer_id', $id)
            ->select('created_at', 'title','id')
            ->get();
    
        return response()->json(['data' => $jobsData], 200);
    }

    function featured_jobs()
    {
        $featuredJobIds = FeaturedJob::pluck('job_id')->toArray();
    
        $data = new Collection();
    
        foreach ($featuredJobIds as $job) {

            $jobId = $job;
    
            $job = Job::find($jobId);
    
            if ($job) {
                $employerId = $job->employer_id;
                    $employer = Employer::find($employerId);
    
                if ($employer) {
                    $data->push([
                        'job' => $job,
                        'employer_info' => $employer,
                    ]);
                }
            }
        }
    
        return $data;
    }
    
    
}
