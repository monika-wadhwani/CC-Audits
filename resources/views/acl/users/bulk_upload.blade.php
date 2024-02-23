@extends('layouts.app')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
<script type='text/javascript' src='https://unpkg.com/popper.js'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css" />
<link rel="stylesheet" href="{{asset('c_dash_css/select2.min.css')}}">
<script type="text/javascript" src="{{ asset('cdn/select2.min.js') }}" ></script>
@section('sh-title')
Users Uploader
@endsection

@section('sh-detail')
Process action dump
@endsection

@section('main')
<style>

.rdoLbl{
  
  margin-top:20px;
}
.right{
  float: right;
}
.modal {
  visibility: hidden;
  opacity: 0;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(77, 77, 77, .7);
  transition: all .4s;
}

.modal:target {
  visibility: visible;
  opacity: 1;
}

.modal__content {
  border-radius: 4px;
  position: relative;
  width: 500px;
  max-width: 90%;
  background: #fff;
  padding: 1em 2em;
}
.modal-dialog, .modal-content {
  height: 70%;
}

.modal-body {
  max-height: calc(100% - 120px);;
  overflow-y: scroll;
}
.modal__footer {
  text-align: right;
  a {
    color: #585858;
  }
  i {
    color: #d02d2c;
  }
}
.modal__close {
  position: absolute;
  top: 10px;
  right: 10px;
  color: #585858;
  text-decoration: none;
}
</style>
<div class="row">
  <div class="col-md-6">
  <div class="kt-portlet">
         
                
         
            <div class="kt-portlet__body">
            
            <button type="button" class="btn btn-primary mt-3 mb-3"  data-toggle="modal" data-target="#exampleModal">
                Add Users
              </button>
            </div>
          
          <!--end::Form-->
        </div>
  </div>

</div>



<!-- 
  users import model
 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Please Add Users</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              {!! Form::open(
                  array(
                    'route' => 'user_import',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    'enctype'=>'multipart/form-data')
                  ) !!}
                <div class="kt-portlet__body">

                  <div class="form-group">
                      <b></b>
                      <input type="file" name="users_file" class="form-control" required/>
                      <span class="form-text text-muted">Max file size:- 50MB. File format:- .xlsx only</span>
                      <small><a href="/road_map_users_import.xlsx" download="download">Click here to donwload sample file.</a></small>
                  </div>
                  <div class="form-group">
                
                  <label>Please Add Roles For Users*</label>
                    <select class="form-control" name="role_id" required="required"  >
                    <option value="0">Select Role</option>
                    <option value="8">Client</option>
                    <option value="5">Quality Analyst(QA)</option>
                    <option value="6">Quality Control</option>
                    <option value="4">Quality Team Leader(QTL)</option>        
                    <option value="14">Agent</option>  
                    <option value="15">Agent Team Leader</option>                  
                    </select>
                  </div>
                  <!-- <div class="form-group" id="qtl_block" style="display:none">
                    <label>Please Select QTL*</label>
                    <select class="form-control" name="qtl_id" required="required" id="selected_tls" onchange="on_qtl_selected(this.value);">
                        <option value="0">QTL</option>
                    </select>
                  </div>
                  <div class="form-group" id="process_block" style="display:none">
                    <label>Please Select Process*</label>
                    <select class="form-control" name="process_id[]" id="selected_process" multiple="true">
                        <option value="0">Process</option>
                    </select>
                  </div>
                  <div id = "qtl_cluster" style="display:none"><h6>Cluster Hierarchy</h6>
                    <div class="form-group row"> 
                      <div class="col-lg-6">
                        <label>Select Parent Client</label>
                        <select id="client_id" onchange="getFilter(this.value)" name="parent_client_id" class="form-control">
                        </select>
                      </div>
                      <div class="col-lg-6">
                        <label>Select Partner</label>
                        <div class="form-group form-box form-group">       
                          <select class="js-example-basic-multiple form-control" id="partner_id" name="partner[]" multiple="true"> 
                          </select>  
                        </div>           
                      </div>
                    </div>
                    <div class="form-group row"> 
                      <div class="col-lg-6">
                        <label>Select Location</label>
                        <div class="form-group form-box form-group"> 
                          <select class="js-example-basic-multiple form-control" id="location_id" name="location[]" multiple="true"> 
                          </select>  
                        </div>       
                      </div>
                      <div class="col-lg-6">
                        <label>Select Process</label>
                        <div class="form-group form-box form-group">        
                          <select class="js-example-basic-multiple form-control" id="process_id" name="cluster_process[]" multiple="true">
                          </select>  
                        </div>     
                      </div>
                    </div>
                  </div> -->
                  <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    <button type="reset" class="btn btn-secondary mt-3">Cancel</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
    <!-- end users import -->

   
@endsection
@section('css')
@include('shared.table_css')

@endsection

@section('js')
<script>


function on_role_selected(val) {
  if(val==5){
    document.getElementById("qtl_block").style.display = "block";
    document.getElementById("qtl_cluster").style.display = "none";
    var base_url = window.location.origin;
    if(val != 0) {
      $.ajax({
      type: "GET",
      url: base_url + "/enterprise/tl_users/"+val,
        success: function(Data){
        $("#selected_tls").html(Data);
        }
      });
    }
  }else if(val==6){
    document.getElementById("process_block").style.display = "block";
    document.getElementById("qtl_block").style.display = "none";
    document.getElementById("qtl_cluster").style.display = "none";
    var base_url = window.location.origin;
    if(val != 0) {
      $.ajax({
      type: "GET",
      url: base_url + "/enterprise/process_allocation/"+val,
        success: function(Data){
        $("#selected_process").html(Data);
        }
      });
    }
  }else if(val==4){
    document.getElementById("process_block").style.display = "none";
    document.getElementById("qtl_block").style.display = "none";
    document.getElementById("qtl_cluster").style.display = "block";
  }else if(val==8){
    document.getElementById("qtl_cluster").style.display = "block";
    document.getElementById("process_block").style.display = "none";
  }
}
function on_qtl_selected(val) {
  if(val){
    document.getElementById("process_block").style.display = "block";
    var base_url = window.location.origin;
    if(val != 0) {
      $.ajax({
      type: "GET",
      url: base_url + "/enterprise/process_allocation/"+val,
        success: function(Data){
        $("#selected_process").html(Data);
        }
      });
    }
  }else{

  }
}

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