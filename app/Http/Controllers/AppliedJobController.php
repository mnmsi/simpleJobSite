<?php

namespace App\Http\Controllers;

use App\Models\Jobs;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppliedJobController extends Controller
{
    public function appliedJob(Request $request)
    {
        $appliedJobs = DB::table('jobs as j')
            ->leftjoin('companies as c', 'j.companyId', 'c.id')
            ->leftjoin('appliedjob as aj', function($query){
                $query->on('aj.jobId', 'j.id')
                ->where('aj.profileId', UserService::getUserId());
            })
            ->select('j.*', 'c.businessName')
            ->paginate(15);

        $data = array(
            'appliedJobs'   => $appliedJobs,
        );

        return view('applied_job.applied_job', $data);
    }

    public function jobView(Request $request)
    {
        $jobData = Jobs::find(decrypt($request->id));

        $data = array(
            'title'       => $jobData->title,
            'description' => $jobData->description,
            'salary'      => $jobData->salary,
            'location'    => $jobData->location,
            'country'     => $jobData->country,
        );

        return view('applied_job.view', $data);
    }
}
