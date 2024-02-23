@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Calibration</h3>
        </div>
        <a href="/calibration"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(
                array(
                'route' => 'calibration.store',
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>"multipart/form-data")
                ) !!}
                <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
                <div class="kt-portlet__body">
                    <div class="kt-form__section kt-form__section--first">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Title*</label>
                                <input type="text" name="title" required class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label>Due Date*</label>
                                <input type="date" class="form-control form-control datepicker ps-0 pt-0 text-start"
                                    id="checkInDate" placeholder="Select date" required name="due_date" />
                            </div>
                        </div><br>
                        <div class="form-group row">
                        <div class="col-md-6">
                                <label>Attachments*</label>
                                <input type="file" name="attachment" class="form-control" />
                            </div>
                        <div class="col-md-6">
                        <label>Master Clibrator*</label>
                            <input type="email" name="master_calibrator" required="required" class="form-control"
                                placeholder="Enter email" />
                        </div>
                        </div>
                       


                        <clibration-create-process-master></clibration-create-process-master>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <h6>Add Calibrators</h6>
                        <span class="form-text text-muted">Please enter same email id for registered user on this
                            application.</span>
                        <br />
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <div id="kt_repeater_1">
                            <div class="form-group  row" id="kt_repeater_1">
                                <div data-repeater-list="calibrator" class="col-lg-10">
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <div class="col-md-6">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Enter Email Id:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input type="email" name="email" class="form-control"
                                                        placeholder="Enter email" />
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
                                <div class="col-lg-4">
                                    <div data-repeater-create="" class="btn btn-sm btn-primary">
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
    <script src="/assets/app/custom/general/crud/forms/widgets/summernote.js" type="text/javascript"></script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
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