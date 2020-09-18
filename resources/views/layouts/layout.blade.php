<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Job Site</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <link rel="stylesheet" href="{{asset('fonts/web-icons/web-icons.min.css')}}">

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script rel="stylesheet" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script rel="stylesheet" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>

<body style="background-color: whitesmoke">
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
        <a class="navbar-brand text-white" href="{{ url('/dashboard') }}">Simple Job Site</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @if(!Auth::check())
                <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ url('/') }}">Login <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ url('/register') }}">Register<span class="sr-only">(current)</span></a>
                </li>
                @else
                <?php $menus = session()->get('loginData')['userMenus'] ?>
                @foreach ($menus as $key => $row)
                    <li class="nav-item active">
                        <a class="nav-link text-white" href="{{ url($key) }}">{{ $row }}<span class="sr-only">(current)</span></a>
                    </li>
                @endforeach
                @endif
            </ul>
        </div>
    </nav>

    @yield('content')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if(Session::has('message'))

            var type = "{{Session::get('alert-type', 'info')}}";

            var console_error = "{{Session::get('console_error')}}";

            console.log(console_error);

            switch (type) {

                case 'info':
                    toastr.info("{{Session::get('message')}}");
                    break;
                case 'success':
                    toastr.success("{{Session::get('message')}}");
                    break;
                case 'warning':
                    toastr.warning("{{Session::get('message')}}");
                    break;
                case 'error':
                    toastr.error("{{Session::get('message')}}");
                    break;
            }
        @endif

        function goBack() {
            window.history.back();
        }
    </script>

</body>
</html>