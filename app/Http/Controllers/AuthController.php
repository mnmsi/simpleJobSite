<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;
use Auth;
use Validator;
use DB;
use Hash;
use Session;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {

            if (!empty(Session::get('loginData'))) {
                return redirect('/dashboard');
            }
        } else {

            return view('login');
        }
    }

    public function login(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            if ($user->role === 'Employee') {

                $userInfo = Profile::where('userId', $user->id)->first();

                $loginData = [
                    'userId' => $userInfo->id,
                    'userName' => $userInfo->firstName,
                    'userMenus' => [
                        'dashboard'  => 'Home',
                        'profile'    => 'Profile',
                        'appliedJob' => 'Applied Job',
                        'logout'     => 'logout'
                    ]
                ];
            } else {

                $userInfo = Companies::where('userId', $user->id)->first();
                $loginData = [
                    'userId' => $userInfo->id,
                    'userName' => $userInfo->firstName,
                    'userMenus' => [
                        'dashboard' => 'Home',
                        'postJob'   => 'Post Job',
                        'logout'    => 'logout'
                    ]
                ];
            }

            session()->put('loginData', $loginData);
            $notification = array(
                'message' => 'Welcome ' . $userInfo->firstName . '',
                'alert-type' => 'success',
            );

            return redirect('/dashboard')->with($notification);
        } else {

            $notification = array(
                'message' => 'Invalid email and password.',
                'alert-type' => 'error',
            );

            return redirect('/')->with($notification);
        }
    }

    public function register(Request $req)
    {
        if ($req->isMethod('post')) {
            return $this->store($req);
        }

        return view('register_user.register');
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

        DB::beginTransaction();
        try {

            $reqData = $req->all();

            $reqData['password'] = Hash::make($reqData['password']);

            $userInsert = User::create($reqData);

            $reqData['userId'] = $userInsert->id;

            if ($req->role === "Employee") {
                Profile::create($reqData);
            } else {
                Companies::create($reqData);
            }
        } catch (\Exception $e) {

            DB::rollback();
            $notification = array(
                'alert-type' => 'error',
                'message'    => 'Something went wrong',
                'consoleMsg' => $e->getFile() . ' ' . $e->getLine() . ' ' . $e->getMessage(),
            );

            return response()->json($notification);
        }

        DB::commit();
        $notification = array(
            'message'    => 'Successfully Inserted',
            'alert-type' => 'success',
        );

        return response()->json($notification);
    }

    public function validationPass($req, $operationType)
    {
        $errorMsg = '';

        if ($req->role === 'Employee') {
            if ($operationType != 'delete') {
                $rules = array(
                    'firstName'                       => 'required',
                    'lastName'                        => 'required',
                    'email'                           => [new Unique('users')],
                    'password'                        => 'required|min:6',
                    'passwordConf'                    => 'min:6|same:password'
                );

                $validator = Validator::make($req->all(), $rules);

                $attributes = array(
                    'firstName'         => 'First Name',
                    'lastName'          => 'Last Name',
                    'email'             => 'Email',
                    'password'          => 'Password',
                    'passwordConf'      => 'Password Confirm',
                );

                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    $errorMsg = implode(' || ', $validator->messages()->all());
                }
            }
        } else {
            if ($operationType != 'delete') {
                $rules = array(
                    'firstName'                       => 'required',
                    'lastName'                        => 'required',
                    'businessName'                    => 'required',
                    'email'                           => [new Unique('users')],
                    'password'                        => 'required|min:6',
                    'passwordConf'                    => 'min:6|same:password'
                );

                $validator = Validator::make($req->all(), $rules);

                $attributes = array(
                    'firstName'         => 'First Name',
                    'lastName'          => 'Last Name',
                    'businessName'      => 'Business Name',
                    'email'             => 'Email',
                    'password'          => 'Password',
                    'passwordConf'      => 'Password Confirm',
                );

                $validator->setAttributeNames($attributes);

                if ($validator->fails()) {
                    $errorMsg = implode(' || ', $validator->messages()->all());
                }
            }
        }

        $isValid = $errorMsg == null ? true : false;

        $validation = array(
            'isValid'   => $isValid,
            'errorMsg'  => $errorMsg
        );

        return $validation;
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/');
    }
}
