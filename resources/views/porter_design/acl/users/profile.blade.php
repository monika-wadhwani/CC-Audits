@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div class="titlTops boxShaow my-2 w-100">
        <h3 class=" mb-1 boxTittle">Profile</h3>
        <p class="text-black-50 m-0 fs-13">Change your Do your survey account details, request your data, and
            more</p>
    </div>
    <div class="row">

        <div class="col-md-7 col-lg-6 col-xl-5">

            <div class="profileBox w-100 boxShaow">
                <div class="py-2 ps-3 border-bottom">
                    <p class="profileHdng">Account</p>
                </div>
                <form method="post" action="{{ action('UserController@updateProfile', Crypt::encrypt($rdata->id)) }}"   accept-charset="UTF-8" class="kt-form" role="form" data-toggle="validator" enctype="multipart/form-data" autocomplete="off">
                    @method('PATCH')
                    @csrf
                    <div class="changePhotSec w-100 d-flex align-items-center border-bottom">
                        <div class="imgBoxPorilfe me-3">
                            <img src="/assets/design/img/{{ $rdata->avatar }}" class="h-100 w-100">
                            <!-- <img src="{{ public_path('img/'.$rdata->avatar) }}" class="h-100 w-100"> -->
                        </div>
                        <div class="contentBoxPorilfe">
                            <h5>{{$rdata->name}}</h5>
                            <button class="btn btn-primary px-3 position-relative"><input type="file"
                                    id="filGallery" name="avatar" multiple="multiple" />Change Image</button>
                        </div>
                    </div>
                    <div class="logDetailsd border-bottom">
                        <p class="profileHdng fw-semibold">Login Details</p>
                        <div>
                            <label class="fs-14">Your email is:</label>
                            <input type="hidden" id="email" name="email" value="{{ $rdata->email }}">
                            <p class="fs-14">{{$rdata->email}}</p>
                        </div>
                        <div>
                            <label class="fs-14">Update Password</label>
                            <div class="d-flex mb-3">
                            <input type="password" id="password" class="form-controller" name="password">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </div>
                        </div>


                    </div>
                    <div class="text-center my-3">
                        <button type="submit" class="btn btn-primary px-5">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.datatables').DataTable();
        });
    </script>
@endsection

@section('css')

@endsection
