@extends('layouts.app')

@section('sh-title')
Audit Estimation Time
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
                <label>Select Process</label>      
                <select name="process_id" class="form-control">
                <option value="0">Select Process</option>
                    @foreach($data as $key => $value)
                      
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>         
            </div>
            <div class="form-group">
                <label>Add Audit Estimation Time(In Mins)</label>
                <input type="number" name="auditor_estimation_time" class="form-control" required>
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