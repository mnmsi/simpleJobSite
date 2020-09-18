@extends('layouts.layout')
@section('content')

<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="container emp-profile">
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    @if(file_exists($picture))
                    <img src="{{ asset($picture) }}"/>
                    @else
                    <img src="{{ asset('images/user.png') }}"/>
                    @endif
                    <br><br>
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                    <h5>
                        {{ $fName .' '. $lName }}
                    </h5>
                    <h6>
                        {{ $skills }}
                    </h6>
                </div>
            </div>
            @if (Auth::user()->role === 'Employee')
            <div class="col-md-2">
                <a href="{{ url('editProfile') }}" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-work">
                    <strong>SKILLS</strong>
                    <p>{{ $skills }}</p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Name</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $fName .' '. $lName }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{ $email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Resume</label>
                            </div>
                            <div class="col-md-6">
                                {{-- <embed src="{{ asset($resume) }}" width="800px" height="2100px" /> --}}
                                <?php
                                    $resumeName =  explode('/', $resume);
                                ?>
                                <a href="{{ asset($resume) }}" target="_blank">{{ end($resumeName) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>           
</div>
@endsection