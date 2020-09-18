<?php

namespace App\Http\Controllers;

use App\Models\Jobs;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;
use Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $req)
    {
        if (!$req->ajax()) {

            return view('post_job.index');
        }

        $limit            = $req->length;
        $search = (empty($req->input('search.value'))) ? null : $req->input('search.value');

        $jobPosts = DB::table('jobs')
            ->where('companyId', UserService::getUserId())
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('salary', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            })
            ->limit($limit)
            ->offset($req->start)
            ->get();

        $totalData = $jobPosts->count();
        $sl        = (int) $req->start + 1;

        foreach ($jobPosts as $key => $row) {

            $jobPosts[$key]->sl = $sl++;
            $jobPosts[$key]->id = encrypt($row->id);
        }

        $data = array(
            "draw"            => intval($req->input('draw')),
            "recordsTotal"    => $totalData,
            "recordsFiltered" => $totalData,
            'data'            => $jobPosts,
        );

        return response()->json($data);
    }

    public function add(Request $req)
    {
        if ($req->isMethod('post')) {
            return $this->store($req);
        }

        return view('post_job.add');
    }

    public function store(Request $req)
    {
        $pass = $this->validationPass($req, $operationType = 'store');
        if ($pass['isValid'] == false) {
            $notification = array(
                'message'    => $pass['errorMsg'],
                'alert-type' => 'error',
            );
            return response()->json($notification);
        }

        $isInsert = Jobs::create($req->all());

        if ($isInsert) {

            $notification = array(
                'message'    => 'Successfully Inserted',
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

    public function edit(Request $req)
    {
        if ($req->isMethod('post')) {
            return $this->update($req);
        }

        $jobData = Jobs::find(decrypt($req->id));

        $data = array(
            'title'       => $jobData->title,
            'description' => $jobData->description,
            'salary'      => $jobData->salary,
            'location'    => $jobData->location,
            'country'     => $jobData->country,
        );

        return view('post_job.edit', $data);
    }

    public function update(Request $req)
    {
        $pass = $this->validationPass($req, $operationType = 'update');
        if ($pass['isValid'] == false) {
            $notification = array(
                'message'    => $pass['errorMsg'],
                'alert-type' => 'error',
            );
            return response()->json($notification);
        }

        try {
            $jobData = Jobs::find(decrypt($req->id));

            $jobData->update($req->all());

        } catch (\Throwable $th) {
            $notification = array(
                'alert-type' => 'error',
                'message'    => 'Something went wrong',
                'consoleMsg' => $th->getFile() . ' ' . $th->getLine() . ' ' . $th->getMessage(),
            );

            return response()->json($notification);
        }

        $notification = array(
            'message'    => 'Successfully Updated',
            'alert-type' => 'success',
        );

        return response()->json($notification);
    }

    public function delete(Request $req)
    {
        $isDelete = Jobs::find(decrypt($req->id))->delete();

        if ($isDelete) {
            $notification = array(
                'message'    => 'Successfully Deleted',
                'alert-type' => 'success',
            );

            return response()->json($notification);
        }
        else {
            $notification = array(
                'alert-type' => 'error',
                'message'    => 'Something went wrong',
            );

            return response()->json($notification);
        }
    }

    public function view(Request $req)
    {
        $jobData = Jobs::find(decrypt($req->id));

        $data = array(
            'title'       => $jobData->title,
            'description' => $jobData->description,
            'salary'      => $jobData->salary,
            'location'    => $jobData->location,
            'country'     => $jobData->country,
        );

        return response()->json($data);
    }

    public function validationPass($req, $operationType)
    {
        $errorMsg = '';

        if ($operationType != 'delete') {

            $rules = array(
                'title'       => 'required',
                'description' => 'required',
                'salary'      => 'required',
                'location'    => 'required',
                'country'     => 'required'
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
