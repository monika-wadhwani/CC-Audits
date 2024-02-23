@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
<div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Add Email</h3>
        </div>
        <a href="/agent_feedback_email/list"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(
                array(
                'route' => 'save_lob',
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Select Client*</label>
                            <select class="form-control" name="client_id" id="client_id"
                                onchange="onChangeClient(this.value);" required="required">

                            </select>
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Select Process*</label>
                            <select class="form-control" name="process_id" id="process_id" required="required">
                            </select>
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label for="exampleFormControlInput" class="form-label">Email Type*</label>
                            <select class="form-control" name="email_type">
                                <option value="#">Select Email Type</option>
                                <option value="to">TO</option>
                                <option value="cc">CC</option>
                            </select>
                        </div>
                        <div class="mb-3 w-50">
                            <label>Email*</label>
                            <input type="email" class="form-control" name="email" required="required">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
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
    <script>
        function onChangeClient(val) {

            var base_url = window.location.origin;

            $.ajax({
                type: "GET",
                url: "/target/get_partners/" + val + "/" + {{ Auth:: User()-> company_id}},
        success: function(Data) {

            $("#partner_id").html(Data);
        }
    });
}

        function onChangeProcess(val) {
            var client_id = document.getElementById("client_id").value;
            var base_url = window.location.origin;

            $.ajax({
                type: "GET",
                url: "/target/get_audit_cycle/" + client_id + "/" + val,
                success: function (Data) {

                    $("#audit_cycle_id").html(Data);
                }
            });
        }



        (function () {

            var base_url = window.location.origin;
            $.ajax({
                type: "GET",
                url: base_url + "/agent_feedback_email/get_client/" + {{ Auth:: User()-> company_id}},
        success: function(Data) {
            $("#client_id").html(Data);
        }
        });

        $.ajax({
            type: "GET",
            url: "/agent_feedback_email/get_process/" + {{ Auth:: User()-> company_id}},
            success: function (Data) {

                $("#process_id").html(Data);
            }
    });
}) ();


    </script>

    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
    @include('shared.form_js')
    @endsection

    @section('css')
    @endsection