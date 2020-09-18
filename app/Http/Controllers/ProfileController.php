<?php

namespace App\Http\Controllers;

use App\Models\AppliedJobs;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\Profile;
use DB;
use Validator;
use Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $profileData = Profile::where('id', UserService::getUserId())->first();

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

    public function applyJob(Request $req)
    {
        $pass = $this->validationPass($req, $operationType = 'isProfileUpdated');
        if ($pass['isValid'] == false) {
            $notification = array(
                'message'    => $pass['errorMsg'],
                'alert-type' => 'error',
            );
            return response()->json($notification);
        }

        $reqData['jobId'] = $req->jobId;
        $reqData['profileId'] = UserService::getUserId();

        $isApply = AppliedJobs::create($reqData);

        if ($isApply) {
            $notification = array(
                'message'    => 'Successfully Applied Job.',
                'alert-type' => 'success',
            );

            return response()->json($notification);
        } else {
            $notification = array(
                'alert-type' => 'error',
                'message'    => 'Something went wrong',
            );

            return response()->json($notification);
        }
    }

    public function editProfile(Request $req)
    {
        if ($req->isMethod('POST')) {
            return $this->update($req);
        }

        $profileData = Profile::find(UserService::getUserId());

        $data = array(
            'fName' => $profileData->firstName,
            'lName' => $profileData->lastName,
            'email' => $profileData->email,
            'picture' => $profileData->picture,
            'resume' => $profileData->resume,
            'skills' => $profileData->skills,
        );

        return view('profile.edit', $data);
    }

    public function update(Request $req)
    {
        $pass = $this->validationPass($req, $operationType = 'editProfile');
        if ($pass['isValid'] == false) {

            $notification = array(
                'message'    => $pass['errorMsg'],
                'alert-type' => 'error',
            );

            return response()->json($notification);
        }

        $profileData = Profile::find(UserService::getUserId());

        DB::beginTransaction();
        try {

            $isUpdate = $profileData->update(['skills' => $req->only('skills')['skills']]);

            if ($isUpdate) {

                $picture = $req->file('picture');
                $picture_url = '';
                $resume_url = '';

                if ($picture) {
                    $pic_path = "uploads/profile_picture/" . UserService::getUserId();
                    $picture_name = hexdec(uniqid());
                    $ext_pic = strtolower($picture->getClientOriginalExtension());
                    $picture_full_name = "picture_" . $picture_name . '.' . $ext_pic;
                    $picture_url = "storage/" . $pic_path . "/" . $picture_full_name;

                    Storage::putFileAs("public/" . $pic_path, $picture, $picture_full_name);
                }

                $resume = $req->file('resume');
                if ($resume) {
                    $resume_path = "uploads/resume/" . UserService::getUserId();
                    $resume_name = hexdec(uniqid());
                    $ext_resume = strtolower($resume->getClientOriginalExtension());
                    $resume_full_name = "resume_" . $resume_name . '.' . $ext_resume;
                    $resume_url = "storage/" . $resume_path . "/" . $resume_full_name;

                    Storage::putFileAs("public/" . $resume_path, $resume, $resume_full_name);
                }

                $profileData->update(['picture' => $picture_url, 'resume' => $resume_url]);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            $notification = array(
                'alert-type' => 'error',
                'message'    => 'Something went wrong!',
            );

            return response()->json($notification);
        }

        DB::commit();
        $notification = array(
            'message'    => 'Successfully Updated.',
            'alert-type' => 'success',
        );

        return response()->json($notification);
    }

    public function validationPass($req, $operationType)
    {
        $errorMsg = '';

        if ($operationType == 'isProfileUpdated') {

            $userProfile = DB::table('profiles')
                ->where('id', UserService::getUserId())
                ->first();

            if (is_null($userProfile->picture) || is_null($userProfile->resume) || is_null($userProfile->skills)) {
                $errorMsg = "Please update your profile!!";
            }
        }

        if ($operationType == 'editProfile') {

            $rules = array(
                'skills'  => 'required',
                'resume'  => 'required|mimes:pdf',
                'picture' => 'required|mimes:jpeg,jpg,png,JPEG,JPG,PNG|max:500',
            );

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                $errorMsg = implode(' || ', $validator->messages()->all());
            }
        }

        $isValid = $errorMsg == null ? true : false;

        $validation = array(
            'isValid'   => $isValid,
            'errorMsg'  => $errorMsg
        );

        return $validation;
    }
}
