@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Create New</h3>
        </div>
        <a href="/user"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(
                array(
                'route' => 'user.store',
                'class' => 'kt-form',
                'role'=>'form',
                'autocomplete'=>"off",
                'data-toggle'=>"validator")
                ) !!}
                <input type="hidden" name="company_id" value="{{Auth::User()->company_id}}">
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Name*</label>
                            <input type="text" name="name" class="form-control" required id="exampleFormControlInput4"
                                placeholder="Name">
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Primary Email (as username)*</label>
                            <input type="text" name="email" class="form-control" required autocomplete="off"
                                id="exampleFormControlInput4" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-contentMet">
                    <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Mobile No.*</label>
                            <input type="text" name="mobile" class="form-control" required id="exampleFormControlInput4"
                                placeholder="Mobile">
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Password*</label>
                            <input type="password" autocomplete="off" name="password" class="form-control" required
                                id="exampleFormControlInput4" placeholder="Password">
                            <small>Your password must be more than 8 characters long, should contain at-least 1
                                Uppercase, 1 Lowercase, 1 Numeric and 1 special character.</small>

                        </div>
                    </div>
                    <div class="form-contentMet">
                    <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Confirm Password*</label>
                            <input type="password" autocomplete="off" placeholder="Confirm Password"
                                name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Roles*</label>
                            <select class="form-control customSelect m-select2" id="kt_select2_1" name="role_id[]"
                                multiple="multiple">
                                @foreach($roles as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <h6>Masters Details</h6>
                            <label for="exampleFormControlInput" class="form-label">Process</label>
                            {{ Form::select('selected_process[]',$process_data,null,['class'=>'form-control customSelect m-select2','id'=>'kt_select2_2','multiple'=>'multiple']) }}
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput" class="form-label">Region</label>
                            {{ Form::select('selected_region[]', $region_data,null,['class' =>'form-control customSelect m-select2', 'id' => 'kt_select2_3', 'multiple' => 'multiple']) }}
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label for="exampleFormControlInput" class="form-label">Language</label>
                            {{ Form::select('selected_language[]', $language_data,null,['class' =>'form-control customSelect m-select2', 'id' => 'kt_select2_4', 'multiple' => 'multiple']) }}
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <h6>Cluster Hierarchy</h6>
                            <label for="exampleFormControlInput" class="form-label">Select Parent Client</label>
                            <select id="client_id" onchange="getFilter(this.value)" name="parent_client_id" class="form-control"></select>
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput4" class="form-label">Select Partner</label>
                            <select class="js-example-basic-multiple form-control" id="partner_id" name="partner[]" multiple="true"></select>
                        </div>
                    </div>
                    <div class="form-contentMet">
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput4" class="form-label">Select Location</label>
                            <select class="js-example-basic-multiple form-control" id="location_id" name="location[]" multiple="true"></select>
                            
                        </div>
                        <div class="mb-3 w-100">
                            <label for="exampleFormControlInput4" class="form-label">Select Process</label>
                            <select class="js-example-basic-multiple form-control" id="process_id" name="process[]" multiple="true"></select>
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
@include('shared.form_js')
    <script type="text/javascript">
  
  function getFilter(client_id) {
    var base_url = window.location.origin;
    $.ajax({
      type: "GET",
      url: base_url + "/getDataPartner/"+client_id,
      success: function(Data){
            $("#partner_id").html(Data);
        }
    });
    $.ajax({
      type: "GET",
      url: base_url + "/getDataLocation/"+client_id,
      success: function(Data){
            $("#location_id").html(Data);
        }
    });
    $.ajax({
      type: "GET",
      url: base_url + "/getDataProcess/"+client_id,
      success: function(Data){
            $("#process_id").html(Data);
        }
    });
  }
  
  
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2({
    });
    
    var base_url = window.location.origin;
    $.ajax({
      type: "GET",
      url: base_url + "/getClientList/0",
      success: function(Data){
            $("#client_id").html(Data);
        }
    });
});

</script>
    @endsection

    @section('css')
    @endsection