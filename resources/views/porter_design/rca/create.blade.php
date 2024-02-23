@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
<div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">RCA Master</h3>
        </div>
        <a href="/rca"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
          
            {!! Form::open(
                  array(
                    'route' => 'rca.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    "enctype"=>'multipart/form-data')
                  ) !!}
                  <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                        <label>Client:</label>
						{{ Form::select('client_id',$client_list,null,['class'=>'form-control','placeholder'=>'Select a client']) }}
                        </div>
                        <div class="mb-3 w-50">
                        <label>Process:</label>
						{{ Form::select('process_id',$process_list,null,['class'=>'form-control','placeholder'=>'Select a process']) }}
                        </div>
                    </div>
                    <div class="form-contentMet">
                    <div class="mb-3 w-50">
                    <label class="col-lg-2 col-form-label">RCA data file:</label>
                    <input type="file" name="rca_data_file" class="form-control"/>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
				<a href="/rca_data.xlsx" download="download">Click to download example RCA data file format, please same structure before upload.</a>
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