@extends('layouts.app')

@section('sh-title')
Scenario Code
@endsection

@section('sh-detail')
Import Data
@endsection

@section('main')
<div class="row">
  <div class="col-md-8">

    <!--begin::Portlet-->
    <div class="kt-portlet">
      <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
          <h3 class="kt-portlet__head-title">
            Details
          </h3>
        </div>
      </div>

      <!--begin::Form-->
      
        {!! Form::open(
                  array(
                    'route' => 'scenerio_tree.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

          <!-- <div class="form-group">
              <label>Dump Date*</label>
              <input type="text" class="form-control" id="kt_datepicker_1" readonly placeholder="Select date" name="dump_date" required/>
          </div>

          <parents-uploader></parents-uploader> -->

          <div class="form-group">
              <label>Scenario Code File*</label>
              <input type="file" name="import_file" class="form-control" required/>
          </div>
          <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
          <a href="/scenario_code_format.xlsx" download="download">Download Format</a>
          
          
        </div>
        <div class="kt-portlet__foot">
          <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
          </div>
        </div>
      </form>

      <!--end::Form-->
    </div>

    <!--end::Portlet-->
  </div>
</div>

@endsection
@section('js')
@include('shared.form_js')
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection