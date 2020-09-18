@extends('layouts.layout')
@section('content')

<div class="container" style="margin-top: 40px">
    <div class="row">
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td width="25%"><b>Title</b></td>
                    <td width="25%" id="title">{{ $title }}</td>
                    <td width="25%"><b>Salary</b></td>
                    <td width="25%" id="salary">{{ $salary }}</td>
                </tr>
                <tr>
                    <td width="25%"><b>Location</b></td>
                    <td width="25%" id="location">{{ $location }}</td>
                    <td width="25%"><b>Country</b></td>
                    <td width="25%" id="country">{{ $country }}</td>
                </tr>
                <tr>
                    <td width="25%"><b>Description</b></td>
                    <td width="75%" colspan="3" id="description">{{ $description }}</td>
                </tr>
            </tbody>
        </table>
        <div class="col-lg-12 d-flex justify-content-center">
            <button class="btn btn-outline-info mr-2" onclick="goBack()">Back</button>
        </div>
    </div>
</div>

@endsection