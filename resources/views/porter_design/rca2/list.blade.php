@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Reason Master</h3>
        </div>
        <a href="/rca"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                Back</button></a>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
            {!! Form::open(
                  array(
                    'url' => '/rca2', 
                    'method'=>'get',
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator",
                    "enctype"=>'multipart/form-data')
                  ) !!}
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label>Client:</label>
                            {{
                            Form::select('client_id',$client_list,null,['class'=>'form-control','placeholder'=>'Select a
                            client']) }}
                        </div>
                        <div class="mb-3 w-50">
                            <label>Process:</label>
                            {{
                            Form::select('process_id',$process_list,null,['class'=>'form-control','placeholder'=>'Select
                            a process']) }}
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="reset" class="btn btn-sm btn-secondary">Cancel</button>
                </div>
                </form>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                <h6>RCA Tree Details</h6>
                <div id="kt_tree_2" class="tree-demo">
												<ul>
													@foreach($data as $ka=>$va)
													<li>
														{{$va->name}}
														<ul>
															<li>
																Action
																<ul>
																	<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($va->id).'&data=1')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>

																	<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($va->id).'&data=1')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																</ul>
															</li>
															@foreach($va->rcatwo1 as $kb=>$vb)
															<li>
																{{$vb->name}}
																<ul>
																	<li>
																		Action
																		<ul>
																			<li data-jstree='{"icon" : "fa fa-plus kt-font-success" }'><a href="{{ url('add_custom_rca2?id='.Crypt::encrypt($vb->id).'&data=1')}}" title="Create"></a></li>
																			<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vb->id).'&data=2')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																			<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vb->id).'&data=2')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																		</ul>
																	</li>
																	@foreach($vb->rcatwo2 as $kc=>$vc)
																	<li>
																		{{$vc->name}}
																		<ul>
																			<li>
																				Action
																				<ul>
																					<li data-jstree='{"icon" : "fa fa-plus kt-font-success" }'><a href="{{ url('add_custom_rca2?id='.Crypt::encrypt($vc->id).'&data=2')}}" title="Create"></a></li>
																					<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vc->id).'&data=3')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																					<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vc->id).'&data=3')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																				</ul>
																			</li>
																			@foreach($vc->rcatwo3 as $kd=>$vd)
																			<li>
																				<ul>
																					<li>
																						Action
																						<ul>
																							<li data-jstree='{"icon" : "fa fa-edit kt-font-warning" }'><a href="{{url('edit_rca2_mode?id='.Crypt::encrypt($vd->id).'&data=4')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit"></a></li>
																							<li data-jstree='{"icon" : "fa fa-trash kt-font-danger" }'><a href="{{url('delete_rca2_mode?id='.Crypt::encrypt($vd->id).'&data=4')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"></a></li>
																						</ul>
																					</li>
																				</ul>
																				{{$vd->name}}
																			</li>
																			@endforeach
																		</ul>
																	</li>
																	@endforeach
																</ul>
															</li>
															@endforeach
														</ul>
													</li>
													@endforeach
												</ul>
											</div>
											<div class="alert alert-outline-primary kt-margin-t-10">
												Note! If you want to add RCA2 mode with it's Rca1,2 and 3 then please use RCA2 -> Create.
											</div>
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