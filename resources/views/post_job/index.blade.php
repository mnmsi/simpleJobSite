@extends('layouts.layout')
@section('content')

<div class="container" style="margin-top: 40px">
    <div class="d-flex justify-content-between">
        <div class=""></div>
        <div class="">
            <a class="btn btn-sm btn-primary" href="{{ url('postJob/add') }}">New Entry</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <table class="table w-full table-hover table-bordered table-striped text-center" id="postTable">
                <thead class="bg-info text-white">
                    <tr>
                        <th style="width: 3%;">SL</th>
                        <th>Job Title</th>
                        <th>Job Salary</th>
                        <th>Job Location</th>
                        <th>Job Country</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@include('post_job.view')


<script>

    $('document').ready(function(){
        getDataIntoTable();
    })

    function getDataIntoTable() {

        $('#postTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            "ajax":{
                "url": "{{ url()->current() }}",
                "dataType": "json",
                "type": "post",
                "data": {}
            },
            "columns": [
                {data: 'sl', name: 'sl', orderable: false, targets: 0, className: 'text-center'},
                { "data": "title" },
                { "data": "salary" },
                { "data": "location" },
                { "data": "country" },
                { "data": "id",  name: 'action', orderable: false },
            ],
            "columnDefs": [{
                "targets": 5,
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass("text-center d-print-none");
                    $(td).closest('tr').attr("cellData", cellData);
                    $(td).html('<a href="javascript:void(0)" title="View" class="btnView" data-target="#jobViewModal" onclick="fnView(\'' +
                        cellData + '\'' +
                        ')"><i class="icon wb-eye mr-2 blue-grey-600"></i></a><a href=' +
                        "{{ url()->current() }}" + '/edit/' + cellData +
                        ' title="Edit" class="btnEdit"><i class="icon wb-edit mr-2 blue-grey-600"></i></a> <a href="javascript:void(0)" onclick="fnDelete(\'' +
                        cellData + '\'' +
                        ');" title="Delete" class=""><i class="icon wb-trash mr-2 blue-grey-600"></i></a>'
                        );
                }
            }]

        });
    }

    function fnView(id) {

        $('#jobViewModal').modal('toggle');

        $.ajax({
            type: "POST",
            url: "{{ url()->current() }}" + '/view',
            data: { id : id},
            dataType: "json",
            success: function (response) {

                $('#title').html(response.title);
                $('#description').html(response.description);
                $('#salary').html(response.salary);
                $('#location').html(response.location);
                $('#country').html(response.country);

            },
            error: function(){
                alert('error!');
            }
        });
    }

    function fnDelete(rowID) {

        swal({
                title: "Are you sure to delete data?",
                text: "Once Delete, this will be permanently delete!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((isConfirm) => {
                if (!isConfirm) {
                    return false;
                }
                var row = $('table tbody tr[cellData=' + '\'' + rowID + '\'' + ']');
                console.log(row);
                $.ajax({
                        url: "{{ url()->current() }}" + '/delete',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: rowID
                        },
                    })
                    .done(function (response) {

                        var row = $('table tbody tr[cellData=' + '\'' + rowID + '\'' + ']');
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
                            row.remove();
                            $('.clsDataTable').DataTable().draw();
                        }

                    })
                    .fail(function () {
                        alert("error");
                    });
            });
        }
</script>
@endsection