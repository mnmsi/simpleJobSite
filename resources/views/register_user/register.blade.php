@extends('layouts.layout')
@section('content')
<link rel="stylesheet" href="{{asset('css/register.css')}}">

<div class="container register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
                <h3>Welcome</h3>
                <p>You can post Job or find the job!</p>
                <a href="{{ url('/') }}" class="btn btn-sm btn-primary">Login<a/><br/>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Employee</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Hirer</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Apply as a Employee</h3>
                        <form action="post" id="employeeForm">
                            @csrf
                            <div class="row register-form">
                                <div class="col-md-10" style="margin-left: 50px">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name *" value="" name="firstName" />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" value="" name="lastName" />
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email *" value="" name="email" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" value="" name="password" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="passwordConf" />
                                    </div>
                                    <input type="hidden" name="role" value="Employee">
                                    {{-- <a class="btn btnRegister" type="submit">Register</a> --}}
                                    <button type="submit" class="btn btnRegister">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h3  class="register-heading">Apply as a Hirer</h3>
                        <form action="post" id="hirerForm">
                            @csrf
                            <div class="row register-form">
                                <div class="col-md-10" style="margin-left: 50px">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="First Name *" value="" name="firstName" />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Last Name *" value="" name="lastName" />
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Business Name *" value="" name="businessName" />
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email *" value="" name="email" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password *" value="" name="password" />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  placeholder="Confirm Password *" value="" name="passwordConf" />
                                    </div>
                                    <input type="hidden" name="role" value="Hirer">
                                    {{-- <a href="{{ url('') }}" class="btn btnRegister" type="submit">Register</a> --}}
                                    <button type="submit" class="btn btnRegister">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $('form').submit(function (event) {
            event.preventDefault();


            if ($(this).attr('id') == 'employeeForm') {
                var sumitingForm = $('#employeeForm');
            }
            else {
                var sumitingForm = $('#hirerForm');
            }

            $.ajax({
                    url: "{{ url()->current() }}",
                    type: 'POST',
                    dataType: 'json',
                    data: sumitingForm.serialize(),
                })
                .done(function (response) {

                    if (response['alert-type'] == 'error') {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: response['message'],
                        });
                       sumitingForm.find(':submit').prop('disabled', false);

                    } else {

                       sumitingForm.trigger("reset");
                       
                        swal({
                            icon: 'success',
                            title: 'Success...',
                            text: response['message'],
                        });

                        setTimeout(function () {
                            window.location = './'
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