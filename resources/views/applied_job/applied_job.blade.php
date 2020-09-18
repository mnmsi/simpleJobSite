@extends('layouts.layout')
@section('content')

<div class="container" style="margin-top: 40px">
    <div class="row">
        @foreach ($appliedJobs as $row)
            <div class="col-lg-8 col-md-10 mx-auto mb-2">
                <div class="card" >
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ url('jobView').'/'.encrypt($row->id) }}">{{ $row->title }}</a>
                                <p class="card-text">{{ $row->businessName }}</p>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        @endforeach

        {{ $appliedJobs->links() }}
    </div>
</div>
@endsection