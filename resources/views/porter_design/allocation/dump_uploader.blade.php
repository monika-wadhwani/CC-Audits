@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="fw-bold mb-1 boxTittle">Raw Data Uploader</h3>
            </div>
            <!-- <a href="#"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                    Back</button></a> -->
        </div>
        {!! Form::open([
            'route' => 'upload_raw_data_dump',
            'class' => 'kt-form',
            'role' => 'form',
            'data-toggle' => 'validator',
            'enctype' => 'multipart/form-data',
        ]) !!}
        <input type="hidden" name="company_id" value="{{ Auth::User()->company_id }}">
        <div class="cardBox my-2 px-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <div class="d-flex align-items-center">
                                <figure class="me-3 m-0 imageCoverbg">
                                    <img src="{{ asset('assets/design/img/Icon awesome-calendar-alt.svg') }}" class=""
                                        alt="calebdericon">
                                </figure>
                                <div class="dateRangePic">
                                    <span class="calenderTo">Dump Date :</span>
                                    <input type="date" id="checkInDate"
                                        class="form-control datepicker ps-0 pt-0 text-start" value="{{ old('dump_date') }}"
                                        name="dump_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 w-50">
                            <label for="exampleFormControlInput" class="form-label">Select Client*</label>
                            {{ Form::select('client_id', $all_client, null, [
                                'class' => 'form-control',
                                'id' => 'client_id',
                                'placeholder' => 'select any one',
                            ]) }}
                        </div>

                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label>Select Client's Partner*</label>
                            <select class="form-control" name="partner_id" id="partner1" required="required">
                                <option value="0">Select One!</option>
                            </select>
                        </div>

                        <div class="mb-3 w-50">
                            <label for="exampleFormControlInput" class="form-label">Select Partner's Process*</label>
                            <select class="form-control" name="process_id" id="process_id" required>
                                <option value="0">Select One!</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-contentMet ">
                        <div class="mb-3 w-50">
                            <div class="d-flex align-items-center">
                                <div class="titleBtm p-2">
                                    <span for="" style="color:blue;">Raw data xlsx**</span>
                                    <input type="file" class="form-control" name="raw_data_file" required />
                                </div><br>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
        <a href="/qm_tool_sample_raw_data_sheet.xlsx" download="download">Sample Raw Data Uploader Sheet.</a><br>
        <a href="/porter_new_dump_format.xlsx" download="download">Sample New Raw Data Format Uploader Sheet.</a>
    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                    url: '<?php echo URL::to('/get_client_partner'); ?>/' + val,
                    type: "GET",
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
                    url: '<?php echo URL::to('/get_partners_process'); ?>/' + val,
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        val: val,
                        client_id: client_id,
                    },
                    success: function(res) {
                        $('#process_id').html('<option value="">Select Option</option>');
                        console.log(res);
                        $.each(res.data, function(key, value) {
                            $('#process_id').append('<option value="' + key + '">' +
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
