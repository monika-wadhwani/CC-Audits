@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
<div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Edit Audit Cycle</h3>
        </div>
        <a href="/audit_cycle"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
          
            {!! Form::model($data,
                  array(
                  'method' => 'PATCH',
                    'url' =>'audit_cycle/'.Crypt::encrypt($data->id),
                    'class' => 'kt-form',
                    'data-toggle'=>"validator")
                  ) !!}
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                        <label>Client Name:</label>
                          {{ Form::select('client_id',$all_clients,$data->client_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                        </div>
                        <div class="mb-3 w-100">
                        <label>Process Name:</label>
                          {{ Form::select('process_id',$all_process,$data->process_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                        <label>QmSheet Name:</label>
                          {{ Form::select('qmsheet_id',$all_qmsheet,$data->qmsheet_id,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
                        </div>
                        <div class="mb-3 w-50">
                        <label>Name:</label>
                          <input type="text" class="form-control" name="name" value="{{ $data->name }}">
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                        <label>Start Date:</label>
                          <input type="text" class="form-control" id="checkInDate" readonly name="start_date" value="{{ $data->start_date }}">
                        </div>
                        <div class="mb-3 w-50">
                        <label>End Date:</label>
                          <input type="text" class="form-control" id="checkInDate" name="end_date" value="{{ $data->end_date }}">
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