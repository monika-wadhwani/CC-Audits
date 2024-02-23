@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2">
            <h4 class="fw-bold mb-1 boxTittle">Report</h4>
            <!-- <a href="{{ url('skill/create') }}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
          <i class="la la-plus"></i>
          New Record
         </a> -->
        </div>
        <div class="tblM w-100 boxShaow px-3">
            <div class="titleBtm p-2">
                <h5 class="m-0 fs-14">Agent Compliance Report
                </h5>
                <div class="d-flex mainSechBox">

                    <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                        <img src="/assets/design/img/filter-icon.png" width="100%">
                    </a>
                </div>
            </div>
            <div class="table-responsive w-100 mainTbl">
                {{ Form::open(array('url' => 'report/new_agent_compliance')) }}
                @csrf
                <div class="form-group row">
                @if(Auth::user()->hasRole('process-owner'))
                    <div class="col-lg-3">
                    {{ Form::select('client_id',$client_list,$client_id,['class'=>'form-control']) }}
                    </div>
                @endif
                
                    <div class="row">
                        <div class="col-sm-2">
                            <select class="form-control" name="partner" required="required" onchange="getLocation(this.value);">
                                <option value="0">Select a Partner</option>
                                @foreach($all_partners as $partner)
                                
                                <option value="{{$partner->id}}">{{$partner->name}}</option>
                                @endforeach
                                <?php 
                                if(Auth::user()->hasRole('client') || Auth::user()->hasRole('process-owner')){
                                    ?>
                                <option value="all">All</option>
    
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="lob" id="lob" required>
                                <option value="0">Select LOB</option>							
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="location" id="location" required>
                                <option value="0">Select a Location</option>							
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control"  name="process" id="process" onchange="getCycle(this.value);" required>
                                <option value="0">Select a Process</option>
                                <option ></option>
                            </select>
                        </div>				
                        <div class="col-sm-2">
                        <select class="form-control" name="date"  id="audit_cycle" required="required" >
                                <option value="0">Select Audit Cycle</option>
                                <option ></option>
                            </select>
                        </div>		
                        <div class="col-sm-2">
                            <input type="submit" style="width: 100px;" class="btn btn-sm btn-primary" value="Search">
                        </div>				
                    </div>						
                </div><br>
            {{ Form::close() }}

                <div class="kt-portlet__body">
                    @if($data)
                    <table class="table mb-0 datatables">
                        <thead>
                            <tr class="blue">
                                <th>Sub Parameters</th>	
                                @foreach($final_data['despositions'] as $vv)
                                <th>{{$vv}}</th>				
                                @endforeach
                                <th>Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-primary"> 
                                <td>Skill Set</td>					
                                @foreach($final_data['audit_single_skill_set'] as $key=>$audit_single_skill_set)
                                <td>{{$audit_single_skill_set}}</td>
                                @endforeach
                                <td>-</td>					
                            </tr>
            
                            <tr class="lightred"> 
                                <td>Audit Count</td>					
                                @foreach($final_data['audit_count'] as $key=>$audit_count)
                                <td>{{$audit_count}}</td>
                                @endforeach
                                <td>{{ $final_data['total_audit_count'] }}</td>					
                            </tr>
                            <tr class="pink"> 
                                <td>Audit Score</td>					
                                @foreach($final_data['audit_score'] as $key=>$audit_score)
                                <td>{{$audit_score}} %</td>
                                @endforeach
                                <td>{{ $final_data['total_audit_score'] }} %</td>					
                            </tr>				
                            @foreach($final_data['data'] as $vv)				
                              @foreach($vv['data'] as $subPara)
                              <?php if($subPara['fatal'] == 1){
                                  $color = "rgb(65, 105, 225)";
                              }else {
                                  $color = "black";
                              }
                              ?>
                              <tr>
                                  <td style="color:{{$color}}">{{$subPara['name']}}</td>
                                  <?php $scr=0; $sco=0; ?>
                                  @foreach($final_data['despositions'] as $v1)
                                  <?php $scr+=$subPara['data'][$v1]['scored']; $sco+=$subPara['data'][$v1]['scorable']; ?>
                                <td style="color:{{$color}}">{{$subPara['data'][$v1]['score']}} % </td>
                                  @endforeach
                                  <td style="color:{{$color}}"><?php if($sco != 0) {
                                      echo round(($scr/$sco)*100);
                                  } else {
                                      echo 0;
                                  }
            
                                  //$subPara['data']['total'] ?>
                                  %</td>
                              </tr>
                              @endforeach
                              <tr class="greenblue">
                                  <td><b> {{$vv['name']}} || NA Count </b></td>
                                  @foreach($final_data['despositions'] as $v1)
                                <td><?=$vv['sum'][$v1] ?> % || {{ $vv['na_count_total'][$v1] }}</td>
                                  @endforeach
                                  <td>{{$vv['sum']['total']}} || {{$vv['sum']['na_count_total_got']}} </td>
                              </tr>		
                            @endforeach		
                        </tbody>
                    </table>
                    @else
                    <p>No Data Found</p>
                    @endif
                </div>
            </div>

        </div>
    @endsection

    @section('js')
        @include('porter_design.shared.agent_dashbaord_js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        {{-- <script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script> --}}
        <script src="/assets/app/custom/general/crud/datatables/basic/scrollable.js" type="text/javascript"></script>
        <script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript">
        </script>
        <script>
            $(document).ready(function() {
                $('.datatables').DataTable();
            });
        </script>
    @endsection
    <script>
        function getCycle(val) {
        
        var base_url = window.location.origin;
        if(val != 0) {
            $.ajax({
            type: "GET",
            url: base_url + "/dashboard/get_partner_audit_cycle/"+val,
            success: function(Data){
                $("#audit_cycle").html(Data);
            }
            });
        
            
        }
        }
        function getLocation(val) {
        
            var base_url = window.location.origin;
            if(val != 0) {
                $.ajax({
                  type: "GET",
                  url: base_url + "/dashboard/get_partner_locations1/"+val,
                  success: function(Data){
                      $("#location").html(Data);
                  }
                });
        
                $.ajax({
                  type: "GET",
                  url: base_url + "/dashboard/get_partner_process1/"+val,
                  success: function(Data){
                      $("#process").html(Data);
                  }
                });
        
                $.ajax({
                  type: "GET",
                  url: base_url + "/dashboard/get_partner_lob/"+val,
                  success: function(Data){
                      $("#lob").html(Data);
                  }
                });
            }
        }
        </script>
    @section('css')
        <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        {!! Html::script('assets/app/custom/general/crud/datatables/extensions/buttons.js') !!}
    @endsection
