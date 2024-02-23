@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Edit Reason Master</h3>
        </div>
        <a href="/reason"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::model($data,
                array(
                'method' => 'PATCH',
                'url' =>'reason/'.Crypt::encrypt($data->id),
                'class' => 'kt-form',
                'data-toggle'=>"validator")
                ) !!}
                <input type="hidden" name="company_id" value="{{AUth::User()->company_id}}">
                <div class="kt-portlet__body">
                    <div class="kt-form__section kt-form__section--first">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Client:</label>
                            <div class="col-lg-4">
                                {{
                                Form::select('client_id',$client_list,$data->client_id,['class'=>'form-control','placeholder'=>'Select
                                a client']) }}
                            </div>
                        </div><br>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Process:</label>
                            <div class="col-lg-4">
                                {{
                                Form::select('process_id',$process_list,$data->process_id,['class'=>'form-control','placeholder'=>'Select
                                a process']) }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Reason Type:</label>
                            <div class="col-lg-4">
                                <input type="text" name="name" value="{{$data->name}}" class="form-control">
                                <span class="form-text text-muted">like People, Process, System</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Label:</label>
                            <div class="col-lg-4">
                                <input type="text" name="label" value="{{$data->label}}" class="form-control">
                                <span class="form-text text-muted">It is for Qm-Sheet identification like People - P1 -
                                    SP1, Process - P1 - SP1, System - P1 - SP1</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Details:</label>
                            <div class="col-lg-4">
                                <textarea class="form-control" name="details">{{$data->details}}</textarea>
                            </div>
                        </div>


                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <div id="kt_repeater_1">
                            <div class="form-group  row" id="kt_repeater_1">
                                <label class="col-lg-2 col-form-label">Reasons:</label>
                                @foreach($data->reasons as $kk=>$vv)
                                <div data-repeater-list="circle" class="col-lg-10">
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <input type="hidden" name="row_id" value="{{$vv->id}}">
                                        <div class="col-md-7">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter reasons of above type" name="reason"
                                                        value="{{$vv->name}}">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill"
                                                @click="onReasonsDelete({{$vv->id}})">
                                                <span>
                                                    <i class="la la-trash-o"></i>
                                                    <span>Delete</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <input type="hidden" name="row_id">
                                        <div class="col-md-7">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter reasons of above type" name="reason">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div data-repeater-delete="" class="btn-sm btn btn-danger btn-pill">
                                                <span>
                                                    <i class="la la-trash-o"></i>
                                                    <span>Delete</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"></label>
                                <div class="col-lg-4">
                                    <div data-repeater-create="" class="btn btn-primary btn-sm">
                                        <span>
                                            <i class="la la-plus"></i>
                                            <span>Add</span>
                                        </span>
                                    </div>
                                </div>
                            </div><br>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-md-4">
                                <button type="Submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
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
    {!! Html::script('assets/vendors/base/vendors.bundle.js')!!}
    {!! Html::script('assets/demo/default/base/scripts.bundle.js')!!}

    <!--end::Global Theme Bundle -->

    <!--begin::Page Vendors(used by this page) -->
    {!! Html::script('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')!!}
    <!--end::Page Vendors -->

    <!--begin::Page Scripts(used by this page) -->
    {!! Html::script('assets/app/custom/general/dashboard.js')!!}
    <!--end::Page Scripts -->
    {!! Html::script('assets/app/bundle/app.bundle.js')!!}
    {!! Html::script('assets/app/custom/general/my-script.js')!!}
    {!! Html::script('assets/app/custom/general/components/extended/sweetalert2.js')!!}

    @include('shared.form_js')
    @endsection

    @section('css')
    @endsection