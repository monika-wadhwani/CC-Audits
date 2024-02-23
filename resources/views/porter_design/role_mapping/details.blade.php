@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Mapping</h3>
        </div>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-lg-6">
                <label> <b> Client List</b></label><br>
                @foreach($clients as $row)
                <div style="display: block" class="card p-1 ml-1">
                    <label>{{$row->name}}</label>
                </div>
                @endforeach
            </div>
            <div class="col-lg-6">
                <label> <b> Partners</b></label><br>
                @foreach($partner as $row)
                <div style="display: block" class="card p-1 ml-1">
                    <label>{{$row->name}}</label>
                </div>
                @endforeach
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-6">
                <label> <b> Process List</b></label><br>
                @foreach($process as $row)
                <div style="display: block" class="card p-1 ml-1">
                    <label>{{$row->name}}</label>
                </div>
                @endforeach
            </div>
            <div class="col-lg-6">
                <label> <b> locations</b></label><br>
                @foreach($location as $row)
                <div style="display: block" class="card p-1 ml-1">
                    <label>{{$row->name}}</label>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    @endsection

    @section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(".customSelect").select2({});
    </script>

    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
    @include('shared.form_js')
    @endsection

    @section('css')
    @endsection