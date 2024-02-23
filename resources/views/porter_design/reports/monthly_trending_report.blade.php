@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

    <div class="container-fluid">
        <div class="titlTops cardBox my-2">
            <h4 class="fw-bold mb-1 boxTittle">Monthly Trending Report</h4>

        </div>
        <div class="tblM w-100 boxShaow px-3">
            <div class="titleBtm p-2">
                <h5 class="m-0 fs-14">Monthly Trending Report</h5>

                <div class="kt-portlet kt-portlet--mobile">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Search / Filter
                            </h3>
                        </div>
                    </div>
                    @if (Auth::user()->hasRole('agent') || Auth::user()->hasRole('agent-tl'))
                        <div class="kt-portlet__body">
                            <form id="form_filters">

                                <div class="row">
                                    <div class="col-sm-2">
                                        <label>Select Report Type:</label>
                                        <select class="form-control" id="report_type" required="required">
                                            <option value="0">Select a report type</option>
                                            <option value="1">Parameter Wise</option>
                                            <option value="2">Sub Parameter Wise</option>
                                            {{-- <option value="3">Agent Wise</option> --}}
                                            <!--<option value="4">Partner Wise</option>
               <option value="5">Circle Wise</option>-->
                                        </select>
                                    </div>

                                    {{-- <div class="col-sm-2">
						<label>Select LOB:</label>
						<select class="form-control" id="lob" required="required">
							<option value="0">Select Lob</option>							
						</select>
					</div>
					<div class="col-sm-2">
						<label>Select a Location:</label>
						<select class="form-control" id="location" required="required">
							<option value="0">Select a Location</option>							
						</select>
					</div> --}}
                                    <div class="col-sm-2">
                                        <label>Select a Process:</label>
                                        <select class="form-control" id="process" onchange="getCycle(this.value);"
                                            required="required">
                                            <option value="0">Select a Process</option>
                                            @foreach ($process as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label>Select Audit Cycle:</label>
                                        <select class="form-control" name="audit_cycle" id="audit_cycle"
                                            required="required">
                                            <option value="0">Select Audit Cycle</option>
                                            <option></option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-md-offset-10">
                                        <br>
                                        <button type="button" onclick="getReport();" style="width: 100%;"
                                            class="btn btn-outline-brand d-block"><i class="fa fa-search"></i>
                                            Search</button>
                                        <!--<button type="button" onclick="getExcel();" class="btn btn-outline-brand"><i class="fa fa-file"></i> Get Excel </button>-->
                                    </div>
                                </div>
                                <br>

                            </form>
                        </div>
                    @else
                        <div class="kt-portlet__body">
                            <form id="form_filters">

                                <div class="row">
                                    <div class="col-sm-2">
                                        <label>Report Type:</label>
                                        <select class="form-control" id="report_type" required="required">
                                            <option value="0">Select a report type</option>
                                            <option value="1">Parameter Wise</option>
                                            <option value="2">Sub Parameter Wise</option>
                                            <option value="3">Agent Wise</option>
                                            <!--<option value="4">Partner Wise</option>
               <option value="5">Circle Wise</option>-->
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Select a Partner:</label>
                                        <select class="form-control" id="partner" required="required"
                                            onchange="getLocation(this.value);">
                                            <option value="0">Select a Partner</option>
                                            @foreach ($all_partners as $partner)
                                                :
                                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                            @endforeach;
                                            <option value="all">ALL</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Select LOB:</label>
                                        <select class="form-control" id="lob" required="required">
                                            <option value="0">Select Lob</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Select Location:</label>
                                        <select class="form-control" id="location" required="required">
                                            <option value="0">Select a Location</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Select Process:</label>
                                        <select class="form-control" id="process" onchange="getCycle(this.value);"
                                            required="required">
                                            <option value="0">Select a Process</option>
                                            <option></option>
                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <label>Audit Cycle:</label>
                                        <select class="form-control" name="audit_cycle" id="audit_cycle"
                                            required="required">
                                            <option value="0">Select Audit Cycle</option>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-10">
                                        <button type="button" onclick="getReport();" style="width: 100%;"
                                            class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <!--<button type="button" onclick="getExcel();" class="btn btn-outline-brand"><i class="fa fa-file"></i> Get Excel </button>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="d-flex mainSechBox">

                    <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                        <img src="/assets/design/img/filter-icon.png" width="100%">
                    </a>
                </div>
            </div>
            <div class="table-responsive w-100 mainTbl" id="table_body">


            </div>
            {{-- <div class="paginationBox d-flex align-items-center py-3 justify-content-end">
            <span>Showing 10 out of {{30}} results</span>
            <div class="pgiNext">
              <a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <a href="#" class="active">1</a>
              <a href="#" class="">2</a>
              <a href="#" class="">3</a>
              <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>
       </div> --}}
        </div>

    </div>

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datatables').DataTable();
        });
    </script>

    <script>
        function getLocation(val) {

            var base_url = window.location.origin;
            if (val != 0) {
                $.ajax({
                    type: "GET",
                    url: base_url + "/dashboard/get_partner_locations1/" + val,
                    success: function(Data) {
                        $("#location").html(Data);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: base_url + "/dashboard/get_partner_process1/" + val,
                    success: function(Data) {
                        $("#process").html(Data);
                    }
                });
                $.ajax({
                    type: "GET",
                    url: base_url + "/dashboard/get_partner_lob/" + val,
                    success: function(Data) {
                        $("#lob").html(Data);
                    }
                });
            }
        }

        function getCycle(val) {

            var base_url = window.location.origin;
            if (val != 0) {
                $.ajax({
                    type: "GET",
                    url: base_url + "/dashboard/get_partner_audit_cycle/" + val,
                    success: function(Data) {
                        $("#audit_cycle").html(Data);
                    }
                });


            }
        }

        $(document).ready(function() {
            $('#dtBasicExample').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });

        function getReport() {
            KTApp.blockPage({
                overlayColor: '#000000',
                type: 'v2',
                state: 'primary',
                message: 'Processing...'
            });
            var base_url = window.location.origin;
            @if (Auth::user()->hasRole('agent') || Auth::user()->hasRole('agent-tl'))
                var partner_id = "";
                var lob = "";
                var location_id = "";
            @else

                var partner_id = document.getElementById("partner").value;
                var lob = document.getElementById("lob").value;
                var location_id = document.getElementById("location").value;
            @endif


            var process_id = document.getElementById("process").value;
            var audit_cycle = document.getElementById("audit_cycle").value;
            var report_type = document.getElementById("report_type").value;

            if (report_type == 1) {
                $.ajax({
                    type: "POST",
                    url: base_url + "/report/mtr_data_parameter_wise",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
                    },
                    data: {
                        audit_cycle: audit_cycle,
                        partner_id: partner_id,
                        process_id: process_id,
                        location_id: location_id,
                        lob: lob
                    },
                    success: function(Data) {
                        KTApp.unblockPage();
                        $("#table_body").html(Data);
                        $("#report_title").html("Parameter Wise");
                    }
                });


            } else if (report_type == 2) {
                $.ajax({
                    type: "POST",
                    url: base_url + "/report/mtr_data_sub_parameter_wise",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
                    },
                    data: {
                        audit_cycle: audit_cycle,
                        partner_id: partner_id,
                        process_id: process_id,
                        location_id: location_id,
                        lob: lob
                    },
                    success: function(Data) {
                        KTApp.unblockPage();
                        $("#table_body").html(Data);
                        $("#report_title").html("Sub-Parameter Wise");
                    }
                });
            } else if (report_type == 3) {
                $.ajax({
                    type: "POST",
                    url: base_url + "/report/mtr_data_agent_wise",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr("content")
                    },
                    data: {
                        audit_cycle: audit_cycle,
                        partner_id: partner_id,
                        process_id: process_id,
                        location_id: location_id,
                        lob: lob
                    },
                    success: function(Data) {
                        KTApp.unblockPage();
                        $("#table_body").html(Data);
                        $("#report_title").html("Agent Wise");
                    }
                });
            } else {
                alert("Invalid report type selection...");
                KTApp.unblockPage();
            }
        }
    </script>
@endsection

@section('css')
@endsection
