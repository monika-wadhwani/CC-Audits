@extends('layouts.app')

@section('sh-title')
QM - Sheet
@endsection

@section('sh-detail')
Create New
@endsection

@section('main')
<div class="row">
  <div class="col-md-7">

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
                    'route' => 'qm_sheet.store', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                  ) !!}
        <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">

        <div class="kt-portlet__body">

          <div class="form-group">
            <label>Client*</label>
            {{ Form::select('client_id',$all_client,null,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
          </div>
          <div class="form-group">
            <label>Process*</label>
            {{ Form::select('process_id',$all_process,null,['class'=>'form-control','required'=>'required','placeholder'=>"Select something"]) }}
          </div>
          <div class="form-group">
            <label>Sheet Name*</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Sheet Code*</label>
            <input type="text" name="code" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Sheet Verion*</label>
            <input type="number" name="version" class="form-control" required>
          </div>
          <!-- <div class="form-group">
            <label>Sheet Banner*</label>
            <input type="file" name="banner" class="form-control">
          </div> -->
          <div class="form-group">
            <label>Details</label>
            <textarea class="form-control" name="details"></textarea>
          </div>
          
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
@endsection