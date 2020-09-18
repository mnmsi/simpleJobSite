@extends('layouts.layout')
@section('content')

<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="container emp-profile">
    <form method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    @if(file_exists(asset($picture)))
                    <img src="{{ asset($picture) }}" alt=""/>
                    @else
                    <img src="{{ asset('images/user.png') }}" alt=""/>
                    @endif
                    @if (Auth::user()->role === 'Employee')
                    <div class="file btn btn-lg btn-primary">
                        Change Photo
                        <input type="file" name="picture">
                    </div>
                    @endif
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
                <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="profile-work">
                    <p>SKILLS</p>
                    <textarea name="skills" id="skills" cols="30" rows="4">{{ $skills }}</textarea>
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
                                <input type="file" name="resume">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>           
</div>

<script>

    $('form').submit(function (event) {
        event.preventDefault();

        $.ajax({
                url: "{{ url()->current() }}",
                type: 'POST',
                dataType: 'json',
                contentType: false,
                data: new FormData(this),
                processData: false,
            })
            .done(function (response) {

                if (response['alert-type'] == 'error') {
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: response['message'],
                    });

                   $('form').find(':submit').prop('disabled', false);

                } else {

                   $('form').trigger("reset");
                   
                    swal({
                        icon: 'success',
                        title: 'Success...',
                        text: response['message'],
                    });

                    setTimeout(function () {
                        window.location = './profile'
                    }, 2000);
                }

            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });

    });
</script>

@endsection