@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="fw-bold mb-1 boxTittle">Re-Label</h3>
            </div>
            <a href="/qm_sheet"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                    Back</button></a>
        </div>
        <div class="cardBox my-2 px-3">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open([
                        'url' => 're_label_update',
                        'class' => 'kt-form',
                        'role' => 'form',
                        'data-toggle' => 'validator',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="re_label_id" value="{{ Crypt::encrypt($data->id) }}">
                    <div class=" mainTbl pe-1">
                        <div class="form-contentMet">
                            <div class="mb-3 w-50">
                                <label>Communication Instance ID(Call ID)*</label>
                                <input type="text" name="info_1" value="{{ $data->info_1 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-50">
                                <label>Client*</label>
                                <input type="text" name="info_2" value="{{ $data->info_2 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Partner*</label>
                                <input type="text" name="info_3" value="{{ $data->info_3 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Audit Date*</label>
                                <input type="text" name="info_4" value="{{ $data->info_4 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Agent Name*</label>
                                <input type="text" name="info_5" value="{{ $data->info_5 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>TL Name*</label>
                                <input type="text" name="info_6" value="{{ $data->info_6 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>QA / QTL Name*</label>
                                <input type="text" name="info_7" value="{{ $data->info_7 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Campaign Name*</label>
                                <input type="text" name="info_8" value="{{ $data->info_8 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Call Sub Type*</label>
                                <input type="text" name="info_9" value="{{ $data->info_9 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Disposition*</label>
                                <input type="text" name="info_11" value="{{ $data->info_11 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Customer Name*</label>
                                <input type="text" name="info_11" value="{{ $data->info_11 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Cusotmer contact number*</label>
                                <input type="text" name="info_12" value="{{ $data->info_12 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>QM-Sheet Version*</label>
                                <input type="text" name="info_13" value="{{ $data->info_13 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>QRC 1*</label>
                                <input type="text" name="info_14" value="{{ $data->info_14 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>QRC for QA*</label>
                                <input type="text" name="info_15" value="{{ $data->info_15 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Language 1*</label>
                                <input type="text" name="info_16" value="{{ $data->info_16 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Language for QA*</label>
                                <input type="text" name="info_17" value="{{ $data->info_17 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Case ID*</label>
                                <input type="text" name="info_18" value="{{ $data->info_18 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Call Time*</label>
                                <input type="text" name="info_19" value="{{ $data->info_19 }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label>Call Duration*</label>
                                <input type="text" name="info_20" value="{{ $data->info_20 }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label>Refrence No.*</label>
                                <input type="text" name="info_21" value="{{ $data->info_21 }}" class="form-control"
                                    required>
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
        <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
        @include('shared.form_js')
    @endsection

    @section('css')
    @endsection
