<h1>New Application submitted.</h1> 

<p>Job id:{{$application->job_id}}</p> 
<p>candidate id:{{$application->candidate_id}}</p>
<p>name:{{$application->name}}</p> 
<p>email:{{$application->email}}</p> 
<p>cover letter:<p>{{$application->cover_letter}}</p></p> 
<p>resume: <a href="{{ url('http://127.0.0.1:8000/' . $application->resume) }}" download>view resume</a></p>
