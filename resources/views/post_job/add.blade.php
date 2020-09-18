@extends('layouts.layout')
@section('content')
<?php use App\Services\UserService; ?>
<div class="container" style="margin-top: 40px">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <form method="POST">
                @csrf

                <input type="hidden" name="companyId" value="{{ UserService::getUserId() }}">
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Enter title" name="title">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <input type="text" class="form-control" name="description" id="description" placeholder="Enter dscription">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Salary</label>
                    <input type="number" class="form-control" name="salary" id="salary" placeholder="Enter salary">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Location</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="Enter location">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Country</label>
                    <input type="text" class="form-control" name="country" id="country" placeholder="Enter country">
                </div>
                <div class="form-group d-flex justify-content-center">
                  <button class="btn btn-outline-info mr-2" onclick="goBack()">Back</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    $('form').submit(function (event) {
        event.preventDefault();

        $.ajax({
                url: "{{ url()->current() }}",
                type: 'POST',
                dataType: 'json',
                data: $('form').serialize(),
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