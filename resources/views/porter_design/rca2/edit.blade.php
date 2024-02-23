@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">RCA2 Master</h3>
        </div>
        <a href="/rca2"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">

                <!--begin::Form-->
                {!! Form::open(
                array(
                'method' => 'PATCH',
                'url' =>'edit_rca2_mode/'.Crypt::encrypt($data->id),
                'class' => 'kt-form',
                'data-toggle'=>"validator", 'role'=>'form')
                )
                !!}
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="company_id" value="{{Crypt::encrypt($data->company_id)}}">
                <input type="hidden" name="client_id" value="{{Crypt::encrypt($data->client_id)}}">
                <input type="hidden" name="process_id" value="{{Crypt::encrypt($data->process_id)}}">
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                        <label class="col-lg-2 col-form-label">Name:</label>
						<input value="{{$data->name}}" type="text" name="name" class="form-control"/>
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