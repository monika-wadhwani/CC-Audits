@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2">
            <h4 class="fw-bold mb-1 boxTittle">Reports</h4>
            <!-- <a href="{{ url('skill/create') }}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
                  <i class="la la-plus"></i>
                  New Record
                 </a> -->
        </div>
        <div class="row cardBox">
            <div class="col-md-12">
                <div class="kt-portlet__body">
                    {!! Form::open([
                        'route' => 'raw_dump_with_audit_report',
                        'class' => 'kt-form',
                        'role' => 'form',
                        'data-toggle' => 'validator',
                        'enctype' => 'multipart/form-data',
                    ]) !!}

                    <div class=" mainTbl pe-1">
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Select Client*</label>
                                {!! Form::select('client_id', $list, null, [
                                    'placeholder' => 'Select One!',
                                    'required' => true,
                                    'class' => 'form-control',
                                    'id' => 'client_id',
                                    'required' => true,
                                ]) !!}
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Select Partner*</label>
                                <select class="form-control" name="partner_id" id="partner1" required="required">
                                    <option value="#">Select One!</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput" class="form-label">Select Location*</label>
                                <select class="form-control" id="location1" name="location_id">
                                    <option value="#">Select One!</option>
                                </select>
                            </div>
                            <div class="mb-3 w-50">
                                <label>Select Process*</label>
                                <select class="form-control" id="process_id1" name="process_id" required="required">
                                    <option value="#">Select One!</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput" class="form-label">Select QM Sheet*</label>
                                <select class="form-control" id="qm_sheet1" name="qm_sheet_id">
                                    <option value="#">Select One!</option>
                                </select>
                            </div>
                            <div class="mb-3 w-50">
                                <label>Select Start Date*</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    required="required" />
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label>Select End Date*</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    required="required" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                    </form>
                </div>
                <a style="margin-left: 70%;!important" onclick="show()"><button class="btn btn-primary float-right">Show
                        Requested Reports</button></a>
                <p style="color:red">{{ $msg }}</p>
            </div>
        </div>
        <div class="tblM w-100 boxShaow px-3">
            <div class="titleBtm p-2">
                <h5 class="m-0 fs-14">Raw dump with Audit data</h5>
                <div class="d-flex mainSechBox">

                    <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                        <img src="/assets/design/img/filter-icon.png" width="100%">
                    </a>
                </div>
            </div>
            <div class="kt-portlet kt-portlet--mobile" id="requested" style="display:none">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Requested Reports
                        </h3><br>

                    </div>
                </div>
                <div class="kt-portlet__body">
                    <p> Reports requested in morning will be available at 2PM.</p>
                    <p> Reports requested after 1PM will be availabe next day morning.</p>

                    <!--begin: Datatable -->
                    <div class="table-responsive w-100 mainTbl">
                        <table class="table mb-0 datatables">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Requested Date</th>
                                    <th>Process</th>
                                    <th>Date Range</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($old_reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report->created_at }}</td>
                                        <td>{{ $report->name }}</td>
                                        <td>{{ $report->filter_start_date }} to {{ $report->filter_end_date }}</td>
                                        <td>
                                            @if ($report->file_location)
                                                <?php
                                                $path_name = str_replace('https://qmtool.s3.ap-south-1.amazonaws.com/', '', $report->file_location);
                                                $url = Storage::disk('s3')->temporaryUrl(
                                                    $path_name,
                                                    now()->addMinutes(3000), //Minutes for which the signature will stay valid
                                                );
                                                ?>
                                                <a href="{{ $url }}">Download Report</a>
                                            @else
                                                Pending
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table-responsive w-100 mainTbl">
                <table class="table mb-0 datatables" id="datatables">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if (Auth::user()->hasRole('client') ||
                                    Auth::user()->hasRole('partner-training-head') ||
                                    Auth::user()->hasRole('partner-operation-head') ||
                                    Auth::user()->hasRole('partner-quality-head') ||
                                    Auth::user()->hasRole('partner-admin'))
                                <th>Auditor Name</th>
                            @else
                                <th>Auditor Name</th>
                            @endif

                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_4 }}
                                @else
                                    Audit Date
                                @endif
                            </th>
                            <th>

                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_3 }}
                                @else
                                    Partner
                                @endif

                            </th>
                            <th>
                                Location

                            </th>
                            <th>Call Id</th>
                            <th>Call File</th>
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_5 }}
                                @else
                                    Agent Name
                                @endif

                            </th>

                            @if (Auth::user()->parent_client == 14)
                                <th>
                                    Agent Feedback
                                </th>
                            @endif

                            <th>Emp. ID</th>
                            <th>Doj</th>
                            <th>LOB</th>
                            <th>
                                Language

                            </th>
                            <th>
                                Language For QA

                            </th>
                            <th>Case Id</th>
                            @if (Auth::user()->hasRole('client') ||
                                    Auth::user()->hasRole('partner-training-head') ||
                                    Auth::user()->hasRole('partner-operation-head') ||
                                    Auth::user()->hasRole('partner-quality-head') ||
                                    Auth::user()->hasRole('partner-admin'))
                            @else
                                <th>Auditor's Total Spend Time</th>
                            @endif

                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_19 }}
                                @else
                                    Call Time
                                @endif

                            </th>
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_20 }}
                                @else
                                    Call Duration
                                @endif

                            </th>
                            <th>Call Type</th>
                            <!-- <th>
                                            @if (isset($labels->qm_sheet_id))
    {{ $labels->info_9 }}
@else
    Call Sub Type
    @endif
                                        </th> -->
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_10 }}
                                @else
                                    Disposition
                                @endif
                            </th>
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_8 }}
                                @else
                                    Compaign Name
                                @endif
                            </th>
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_11 }}
                                @else
                                    Customer Name
                                @endif
                            </th>
                            <th>
                                @if (isset($labels->qm_sheet_id))
                                    {{ $labels->info_12 }}
                                @else
                                    Customer Phone
                                @endif
                            </th>
                            <!-- <th>Audit Date</th>
                                    <th>Partner</th>
                                    <th>Location</th>
                                    <th>Call Id</th>
                                    <th>Agent Name</th>
                                    <th>Emp. ID</th>
                                    <th>Doj</th>
                                    <th>LOB</th>
                                    <th>Language</th>
                                    <th>Case Id</th>
                                    <th>Call Time</th>
                                    <th>Call Duration</th>
                                    <th>Call Type</th>
                                    <th>Call Sub Type</th>
                                    <th>Disposition</th>
                                    <th>Compaign Name</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone</th> -->
                            <th>Reference Number</th>
                            <th>QRC</th>
                            <th>Overall Summary</th>
                            <th>With Fatal Score</th>
                            <th>Without Fatal Score</th>
                            <th>Brand Name</th>
                            <th>Circle</th>
                            <th>Info 1</th>
                            <th>Info 2</th>
                            <th>Info 3</th>
                            <th>Info 4</th>
                            <th>Info 5</th>
                            <th>CRN No./Order ID</th>
                            <th>Caller ID:</th>
                            <th>
                                Vehicle Type
                            </th>
                            <th>Caller Type</th>
                            <th>Order Stage</th>
                            <th>Audit Type</th>
                            <th>Issues</th>
                            <th>Sub Issues</th>
                            <th>Scanerio</th>
                            <th>Scanerio Codes</th>
                            <th>Error Reason Type</th>
                            <th>Error Reasons</th>
                            <th>Error Code</th>


                            @foreach ($repeater_param_data as $kk => $vv)
                                @foreach ($vv as $kkb => $vvb)
                                    <th>{{ $vvb['name'] }}</th>
                                    <th>Scored</th>
                                    <th>Scorable</th>
                                    <th>Reason Type</th>
                                    <th>Reason</th>
                                    <th>Remark</th>
                                @endforeach
                            @endforeach
                            <th>Total Scored</th>
                            <th>Total Scorable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $kk => $vv)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if (Auth::user()->hasRole('client') ||
                                        Auth::user()->hasRole('partner-training-head') ||
                                        Auth::user()->hasRole('partner-operation-head') ||
                                        Auth::user()->hasRole('partner-quality-head') ||
                                        Auth::user()->hasRole('partner-admin'))
                                    <td>{{ $vv['auditor'] }}</td>
                                @else
                                    <td>{{ $vv['auditor'] }}</td>
                                @endif
                                <td>{{ $vv['audit_date'] }}</td>
                                <td>{{ $vv['partner'] }}</td>
                                <td>{{ $vv['location'] }}</td>
                                <td>{{ $vv['temp_raw_data']->call_id }}</td>
                                <td>{{ $vv['good_bad_call_file'] }}</td>
                                <td>{{ $vv['temp_raw_data']->agent_name }}</td>
                                @if (Auth::user()->parent_client == 14)
                                    <td>{{ $vv['agent_feedback'] }}</td>
                                @endif
                                <td>{{ $vv['temp_raw_data']->emp_id }}</td>
                                <td>{{ $vv['temp_raw_data']->doj }}</td>
                                <td>{{ $vv['temp_raw_data']->lob }}</td>
                                <td>{{ $vv['language_2'] }}</td>
                                <td>{{ $vv['language_for_qa'] }}</td>
                                <td>{{ $vv['case_id'] }}</td>
                                @if (Auth::user()->hasRole('client') ||
                                        Auth::user()->hasRole('partner-training-head') ||
                                        Auth::user()->hasRole('partner-operation-head') ||
                                        Auth::user()->hasRole('partner-quality-head') ||
                                        Auth::user()->hasRole('partner-admin'))
                                @else
                                    <td>
                                        <?php
                                        $h = intval($vv['auditor_time_spend'] / 3600);
                                        
                                        $vv['auditor_time_spend'] = $vv['auditor_time_spend'] - $h * 3600;
                                        $m = intval($vv['auditor_time_spend'] / 60);
                                        $s = $vv['auditor_time_spend'] - $m * 60;
                                        ?>
                                        @if (!isset($h))
                                            {{ $h }} Hours, {{ $m }} Minutes, {{ $s }}
                                            Secs.
                                        @else
                                            {{ $m }} Minutes, {{ $s }} Secs.
                                        @endif

                                    </td>
                                @endif
                                <td>{{ $vv['temp_raw_data']->call_time }}</td>
                                <td>{{ $vv['temp_raw_data']->call_duration }}</td>
                                <td>{{ $vv['call_types'] }}</td>
                                <!-- <td>{{ $vv['temp_raw_data']->call_sub_type }}</td> -->
                                <td>{{ $vv['temp_raw_data']->disposition }}</td>
                                <td>{{ $vv['temp_raw_data']->campaign_name }}</td>
                                <td>{{ $vv['temp_raw_data']->customer_name }}</td>
                                <td>{{ $vv['temp_raw_data']->phone_number }}</td>
                                <td>{{ $vv['refrence_number'] }}</td>
                                <td>{{ $vv['qrc_2'] }}</td>
                                <td>{{ $vv['overall_summary'] }}</td>
                                <td>{{ $vv['with_fatal_score_per'] }}</td>
                                <td>{{ $vv['without_fatal_score'] }}</td>
                                <td>{{ $vv['temp_raw_data']->brand_name }}</td>
                                <td>{{ $vv['temp_raw_data']->circle }}</td>
                                <td>{{ $vv['temp_raw_data']->info_1 }}</td>
                                <td>{{ $vv['temp_raw_data']->info_2 }}</td>
                                <td>{{ $vv['temp_raw_data']->info_3 }}</td>
                                <td>{{ $vv['temp_raw_data']->info_4 }}</td>
                                <td>{{ $vv['temp_raw_data']->info_5 }}</td>
                                <td>{{ $vv['order_id'] }}</td>
                                <td>{{ $vv['caller_id'] }}</td>
                                <td>{{ $vv['vehicle_type'] }}</td>
                                <td>{{ $vv['caller_type'] }}</td>
                                <td>{{ $vv['order_stage'] }}</td>
                                <td>{{ $vv['audit_type'] }}</td>
                                <td>{{ $vv['issues'] }}</td>
                                <td>{{ $vv['sub_issues'] }}</td>

                                <td>{{ $vv['scanerio'] }}</td>
                                <td>{{ $vv['scanerio_codes'] }}</td>
                                <td>{{ $vv['error_reason_type'] }}</td>
                                <td>{{ $vv['error_code_reasons'] }}</td>
                                <td>{{ $vv['new_error_code'] }}</td>
                                @php($total_scored = 0)
                                @php($total_scorable = 0)
                                @php($is_critical = 0)
                                @foreach ($vv['audit'] as $kk => $vv)
                                    @foreach ($vv as $kkb => $vvb)
                                        <?php if ($vvb['observation'] == 'Critical') {
                                            $is_critical = 1;
                                        } ?>
                                        <td>{{ $vvb['observation'] }}</td>
                                        <td>{{ $vvb['scored'] }}</td>
                                        <td>{{ $vvb['scorable'] }}</td>

                                        <td>{{ $vvb['reason_type'] }}</td>
                                        <?php
                                        $string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
                                        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
                                        $string = preg_replace('/-+/', ' ', $string);
                                        ?>

                                        <td>{{ strip_tags($string) }}</td>



                                        <?php
                                        $string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
                                        $string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
                                        $string = preg_replace('/-+/', ' ', $string);
                                        ?>

                                        <td>{{ strip_tags($string) }}</td>



                                        @php($total_scored += $vvb['scored'])
                                        @php($total_scorable += $vvb['scorable'])
                                    @endforeach
                                @endforeach
                                <td>
                                    <?php if ($is_critical == 1) {
                                        echo 0;
                                    } else {
                                        if ($total_scored >= $total_scorable) {
                                            echo $total_scorable;
                                        } else {
                                            echo $total_scored;
                                        }
                                    } ?>
                                </td>
                                <td>
                                    <?php if ($total_scorable > 100) {
                                        echo 100;
                                    } else {
                                        echo $total_scorable;
                                    } ?>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>

    <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css ">

    <script src=" https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js "></script>
    <script src=" https://code.jquery.com/jquery-3.7.0.js"></script>





    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatables').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                scrollX: true,
                scrollY: true,
                scrollCollapse: true
            });
        });
    </script>
@endsection
@section('css')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
    function show() {
        document.getElementById("requested").style.display = "block";
    }
    $(document).ready(function() {
        $('#client_id').change(function() {
            var val = $(this).val();
            $.ajax({
                url: '<?php echo URL::to('/report/get_rdr_client_partner_list'); ?>/' + val,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    val: val,
                },
                success: function(res) {
                    $('#partner1').html('<option value="">Select Option</option>');
                    console.log(res);
                    $.each(res.data, function(key, value) {
                        $('#partner1').append('<option value="' + key + '">' +
                            value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
        $('#partner1').change(function() {
            var sp_var = $(this).val();
            var val = null;
            if (sp_var == '%') {
                val = "All";
            } else {
                val = $(this).val();
            }
            var client_id = $('#client_id').val();
            $.ajax({
                url: '<?php echo URL::to('/report/get_rdr_client_partner_location_list'); ?>/' + val + '/' + client_id,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    val: val,
                    client_id: client_id,
                },
                success: function(res) {
                    $('#location1').html('<option value="">Select Option</option>');
                    $('#process_id1').html('<option value="">Select Option</option>');
                    console.log(res);
                    $.each(res.data.partner_location_list, function(key, value) {
                        $('#location1').append('<option value="' + key + '">' +
                            value + '</option>');
                    });
                    $.each(res.data.partner_process_list, function(key, value) {
                        $('#process_id1').append('<option value="' + key + '">' +
                            value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
        $('#process_id1').change(function() {
            var val = $(this).val();
            var client_id = $('#client_id').val();
            $.ajax({
                url: '<?php echo URL::to('/report/get_rdr_client_process_qmsheeet_list'); ?>/' + client_id + '/' + val,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    val: val,
                    client_id: client_id,
                },
                success: function(res) {
                    $('#qm_sheet1').html('<option value="">Select Option</option>');
                    console.log(res);
                    $.each(res.data, function(key, value) {
                        $('#qm_sheet1').append('<option value="' + key + '">' +
                            value + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
