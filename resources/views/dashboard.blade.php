@extends('layouts.layout')
@section('content')

<div class="container" style="margin-top: 40px">
    <div class="row">
        @if (Auth::user()->role === 'Employee')
            <div class="col-12 d-flex justify-content-center">
                <strong><p>Available Jobs</p></strong>
            </div>
            @foreach ($jobs as $row)
                <div class="col-lg-8 col-md-10 mx-auto mb-2">
                    <div class="card" >
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ url('jobView').'/'.encrypt($row->id) }}">{{ $row->title }}</a>
                                    <p class="card-text">{{ $row->businessName }}</p>
                                </div>
                                <div>
                                    @if (in_array($row->id, $isApply))
                                    <p class="card-text text-primary">Applied</p>
                                    @else
                                    <a class="btn btn-sm btn-primary" href="javascript:void(0)" onclick="fnApply({{ $row->id }})">Apply</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $jobs->links() }}

        @else
            <div class="col-12 d-flex justify-content-center">
                <strong><p>Applied Employee</p></strong>
            </div>
            @foreach ($appliedEmp as $row)
                <div class="col-lg-8 col-md-10 mx-auto mb-2">
                    <div class="card" >
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ url('profileView').'/'.encrypt($row->id) }}">{{ $row->firstName.' '.$row->lastName }}</a>
                                    <p class="card-text">{{ $row->skills }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script>
    @if(Auth::user()->role === 'Employee')

        function fnApply(jobId) {

            $.ajax({
                type: "POST",
                url: "{{ url('apply') }}",
                data: { jobId : jobId},
                dataType: "json",
                success: function (response) {
                    if (response['alert-type'] == 'error') {
                        swal({
                            icon: 'error',
                            title: 'Oops...',
                            text: response['message'],
                        });
                    } else {
                        swal({
                            icon: 'success',
                            title: 'Success...',
                            text: response['message'],
                        });

                        location.reload();
                    }
                },
                error: function(){
                    swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Something went wrong! Try again..",
                    });
                }
            });
        }
    @endif
</script>

@endsection