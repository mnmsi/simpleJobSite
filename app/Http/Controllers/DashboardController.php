<?php

namespace App\Http\Controllers;

use App\Models\AppliedJobs;
use App\Models\Jobs;
use App\Models\Profile;
use App\Services\UserService;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role === 'Employee') {

            $jobs = DB::table('jobs as j')
                ->leftjoin('companies as c', 'j.companyId', 'c.id')
                ->select('j.id', 'j.title', 'c.businessName')
                ->paginate(15);

            $isApply = AppliedJobs::where('profileId', UserService::getUserId())
                ->pluck('jobId')
                ->all();

            $data = array(
                'jobs'       => $jobs,
                'isApply'    => $isApply,
            );
        } else {

            $userId = UserService::getUserId();
            /* $appliedJobEmp = DB::table('jobs as j')
                ->where('j.companyId', $userId)
                ->leftjoin('appliedjob as aj', 'j.id', 'aj.jobId')
                ->leftjoin('profiles as p', 'aj.profileId', 'p.id')
                ->select('p.*')
                ->paginate(15); */
            $appliedJobEmp = DB::table('appliedjob as aj')
                ->leftjoin('profiles as p', 'aj.profileId', 'p.id')
                ->leftjoin('jobs as j', function($query) use ($userId){
                    $query->on('aj.jobId', 'j.id')
                        ->where('j.companyId', $userId);
                })
                ->select('p.*')
                ->paginate(15);

            $data = array(
                'appliedEmp' => $appliedJobEmp,
            );
        }

        return view('dashboard', $data);
    }

    public function profileView(Request $request)
    {
        $profileData = Profile::find(decrypt($request->id));

        $data = array(
            'fName' => $profileData->firstName,
            'lName' => $profileData->lastName,
            'email' => $profileData->email,
            'picture' => $profileData->picture,
            'resume' => $profileData->resume,
            'skills' => $profileData->skills,
        );

        return view('profile.profile', $data);
    }
}
