@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="fw-bold mb-1 boxTittle">Manage Your Team
                </h3>
            </div>
            <a href="/allocation"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                    Back</button></a>
        </div>
        <div class="cardBox my-2 px-3">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open([
                        'route' => 'store_qa_sheet',
                        'class' => 'kt-form',
                        'role' => 'form',
                        'data-toggle' => 'validator',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class=" mainTbl pe-1">
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput" class="form-label">Select Client*</label>
                                {{ Form::select('selected_qtl', $all_client, null, ['class' => 'form-control', 'id' => 'selected_qtl', 'placeholder' => 'select any one']) }}
                            </div>
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput" class="form-label">Select Sheet*</label>
                                <select class="form-control customSelect m-select2" name="sheet_id[]" id="sheet_ids"
                                    required="required" multiple="multiple">
                                    <option value="#">Select One!</option>
                                </select>
                                {{-- {{ Form::select('selected_region[]', $all_client,null,['class' =>'form-control customSelect m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }} --}}
                            </div>

                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label for="exampleFormControlInput" class="form-label">Select QTL*</label>
                                <select class="form-control" name="qtl_id" id="qtl_id" required>
                                    <option value="#">Select One!</option>
                                </select>
                                {{-- {{ Form::select('qtl_id[]', $all_client,null,['class' =>'form-control customSelect m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }} --}}
                            </div>

                        </div>
                        <div class="table-responsive w-100 mainTbl">
                            <table class="table mb-0 datatables">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th title="Field #1">
                                            Name
                                        </th>
                                        <th title="Field #2">
                                            Email
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="my_team_list">

                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                    </form>
                </div>
            </div><br>
            <div class="table-responsive w-100 mainTbl">
                <table class="table mb-0 datatables">
                    <thead>
                        <tr>
                            <th title="Field #1">#</th>
                            <th title="Field #2">
                                Name
                            </th>
                            <th title="Field #2">
                                Email
                            </th>
                        </tr>
                    </thead>
                    <tbody id="selected_sheet_ids">

                    </tbody>
                </table>
            </div>

        </div>
    @endsection

    @section('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.datatables').DataTable();
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#selectAll').change(function() {
                    $('.selectCheckbox').prop('checked', this.checked);
                });

                $('.selectCheckbox').change(function() {
                    if ($('.selectCheckbox:checked').length === $('.selectCheckbox').length) {
                        $('#selectAll').prop('checked', true);
                    } else {
                        $('#selectAll').prop('checked', false);
                    }
                });
            });
        </script>
        <script>
            $(".customSelect").select2({});
        </script>
        <script>
            $(document).ready(function() {
                $('#selected_qtl').change(function() {
                    var val = $(this).val();
                    $.ajax({
                        url: '<?php echo URL::to('get_sheets_by_client'); ?>/' + val,
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            val: val,
                        },
                        success: function(res) {
                            $('#sheet_ids').html('<option value="">Select Option</option>');
                            console.log(res);
                            $.each(res.data, function(index, value) {
                                $('#sheet_ids').append('<option value="' + value.id + '-' +
                                    value.process.id + '">' + value.name + '-' + value
                                    .process.name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#selected_qtl').change(function() {
                    var val = $(this).val();
                    $.ajax({
                        url: '<?php echo URL::to('get_qtls_by_client'); ?>/' + val,
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            val: val,
                        },
                        success: function(res) {
                            $('#qtl_id').html('<option value="">Select Option</option>');
                            console.log(res);
                            $.each(res.data, function(key, value) {
                                $('#qtl_id').append('<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#qtl_id').change(function() {
                    var val = $(this).val();
                    $.ajax({
                        url: '<?php echo URL::to('get_qtl_team'); ?>/' + val,
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            val: val,
                        },
                        dataType: 'html',
                        success: function(res) {
                            $('#qtl_id').html('<option value="">Select Option</option>');
                            console.log(res);
                            $('#my_team_list').html(res);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#sheet_ids').change(function() {
                    // var val = $(this).val();
                    var val = $(this).val().toString();
                    var valArray = val.split('-');
                    var firstElement = valArray[0];
                    $.ajax({
                        url: '<?php echo URL::to('get_qm_sheet_associated_qa'); ?>/' + firstElement,
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            val: firstElement,
                        },
                        dataType: 'html',
                        success: function(res) {
                            console.log(res);
                            $('#selected_sheet_ids').html(res);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>

        @include('shared.form_js')
    @endsection

    @section('css')
    @endsection
