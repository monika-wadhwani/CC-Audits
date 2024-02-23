@extends('porter_design.layouts.app2')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2">
            <h4 class="fw-bold mb-1 boxTittle">QC Report</h4>
            <!-- <a href="{{ url('skill/create') }}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
                  <i class="la la-plus"></i>
                  New Record
                 </a> -->
        </div>
        <div class="row cardBox">
            <div class="col-md-12">
                <div class="kt-portlet__body">
                    {!! Form::open([
                        'route' => 'qc_report',
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
                </div><br>
            </div>
        </div>
        <div class="tblM w-100 boxShaow px-3">
            <div class="titleBtm p-2">
                <h5 class="m-0 fs-14">Requested Reports</h5>
                <div class="d-flex mainSechBox">

                    <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                        <img src="/assets/design/img/filter-icon.png" width="100%">
                    </a>
                </div>
            </div>


            <div class="table-responsive w-100 mainTbl">
                <table class="table mb-0 datatables" id="datatables">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand</th>
                            <th>Language by QA</th>
                            <th>Circle Name</th>
                            <th>Call Id</th>
                            <th>Auditor Name</th>
                            <th>Participant Phone Number</th>
                            <th>Agent User Department (Partner)</th>
                            <th>Evaluation Creation Date</th>
                            <th>Customer LOB (Pre/Post)</th>
                            <th>Segment Date & Start Time</th>
                            <th>Segment Duration</th>
                            <th>Agent CRM ID</th>
                            <th>Evaluation Score</th>
                            <th>Reason for Call (CRM Sub Type)</th>
                            <th>DFF 1</th>
                            <th>Overall Remarks</th>
                            <th>QC Date</th>
                            <th>QC Defect Parameter Name</th>
                            <th>QC Defect Count</th>
                            <th>Variance %</th>
                            <th>QC Remarks (After made changes in any parameter)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $kk => $vv)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vv['brand'] }}</td>
                                <td>{{ $vv['language_2'] }}</td>
                                <td>{{ $vv['circle'] }}</td>
                                <td>{{ $vv['call_id'] }}</td>
                                <td>{{ $vv['auditor'] }}</td>
                                <td>{{ $vv['customer_phone'] }}</td>
                                <td>{{ $vv['partner'] }}</td>
                                <td>{{ $vv['evaluation_date'] }}</td>
                                <td>{{ $vv['lob'] }}</td>
                                <td>{{ $vv['call_time'] }}</td>
                                <td>{{ $vv['call_duration'] }}</td>
                                <td>{{ $vv['agent_id'] }}</td>
                                <td>{{ $vv['evaluation_score'] }}</td>
                                <td>{{ $vv['call_sub_type'] }}</td>
                                <td>{{ $vv['dff_1'] }}</td>
                                <td>{{ $vv['overall_remark'] }}</td>
                                <td>{{ $vv['qc_date'] }}</td>
                                <td>{{ $vv['qc_deffect_parameter'] }}</td>
                                <td>{{ $vv['qc_deffect_parameter_count'] }}</td>
                                <td>{{ $vv['variance'] }}</td>
                                <td>{{ $vv['qc_remark'] }}</td>
                                <td>{{ $vv['status'] }}</td>
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

    <script>
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
@endsection
@section('css')
@endsection
