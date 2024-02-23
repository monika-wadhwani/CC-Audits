@extends('layouts.app')

@section('sh-title')
Audit
@endsection

@section('sh-detail')
Done
@endsection

@section('main')

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Filter:- Audit Date
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<form>
			<div class="form-group row">
				<div class="col-lg-4">
					<input type="text" name="start_date" class="form-control" id="kt_datepicker_1" readonly placeholder="Select start audit date" />
				</div>
				<div class="col-lg-4">
					<input type="text" name="end_date" class="form-control" id="kt_datepicker_2" readonly placeholder="Select end audit date" />
				</div>
				<div class="col-lg-4">
					<button type="submit" class="btn btn-brand">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>




<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<span class="kt-portlet__head-icon">
				<i class="kt-font-brand flaticon2-line-chart"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				List
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #8">
							View Audit
						</th>
						
						<th title="Field #4">
							Call Id
						</th>
						<th title="Field #6">
							Agent
						</th>
						<th title="Field #6">
							Audit Date
						</th>
						<th title="Field #6">
							Approval TAT
						</th>
						<th title="Field #7">
							Audit Status
						</th>
						<th title="Field #7">
							Score
						</th>
						<th title="Field #7">
							Accepted 
						</th>
						<th title="Field #7">
							Rejected
						</th>
						<th title="Field #7">
							Rebuttal Seen Status
						</th>
						
						<th title="Field #7">
							Action
						</th>

				</tr>
			</thead>
			<tbody>
				@if(Auth::user()->hasRole('agent-tl'))
					@foreach($data as $row)
				
					@if(audit_rebuttal_status($row->audit->rebuttal_status)=='Raised' and $row->audit->audit_rebuttal_not_validate_action->count() > 0)
					<tr>
						<td>{{$loop->iteration}}</td>
						<td nowrap>
							<a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
								<i class="la la-eye"></i>
							</a>
						</td>
						
						<td>
							{{$row->call_id}}
						</td>
						<td>
							{{$row->agent_name}}
						</td>
						<td>
							{{date_time_to_user($row->audit->audit_date)}}
						</td>
						<td>
							{{date_time_to_user($row->audit->agent_tl_approval_tat)}}
						</td>
						
						<td>
							@if($row->audit->is_critical)
							<span class="text-danger">Fatal</span>
							@else
							<span class="text-success">Non-Fatal</span>
							@endif
						</td>
						<td>
							{{round($row->audit->overall_score)}}%
						</td>
						<td>
							{{audit_rebuttal_status($row->audit->rebuttal_status)}} || {{$row->audit->audit_rebuttal->count()}}
						</td>
						<td>
							{{$row->audit->audit_rebuttal_accepted->count()}}
						</td>
						<td>
							{{$row->audit->audit_rebuttal_rejected->count()}}
						</td>
						@if(Auth::user()->hasRole('agent-tl'))
						<td>
							<a href="{{url('valid_invalid/'.Crypt::encrypt($row->audit->id)."/1")}}"><span class="kt-badge kt-badge--success kt-badge--inline">Valid</span></a>
							<a href="{{url('valid_invalid/'.Crypt::encrypt($row->audit->id)."/2")}}"><span class="kt-badge kt-badge--warning kt-badge--inline">Invalid</span></a>
						</td>
						@endif					
					</tr>
					@endif
            		@endforeach
				@else 
					@foreach($data as $row)
						
						<tr>
							<td>{{$loop->iteration}}</td>
							<td nowrap>
								<a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
									<i class="la la-eye"></i>
								</a>
							</td>
							
							<td>
								{{$row->call_id}}
							</td>
							<td>
								{{$row->agent_name}}
							</td>
							<td>
								{{date_time_to_user($row->audit->audit_date)}}
							</td>
							<td>
								{{date_time_to_user($row->audit->agent_tl_approval_tat)}}
							</td>
							
							<td>
								@if($row->audit->is_critical)
								<span class="text-danger">Fatal</span>
								@else
								<span class="text-success">Non-Fatal</span>
								@endif
							</td>
							<td>
								{{round($row->audit->overall_score)}}%
							</td>
							<td>
								{{audit_rebuttal_status($row->audit->rebuttal_status)}} || {{$row->audit->audit_rebuttal->count()}}
							</td>
							<td>
								{{$row->audit->audit_rebuttal_accepted->count()}}
							</td>
							<td>
								{{$row->audit->audit_rebuttal_rejected->count()}}
							</td>
							
							<td>
								<a href="{{url('feedback_accept/'.Crypt::encrypt($row->audit->id)."/1")}}"><span class="kt-badge kt-badge--success kt-badge--inline">Accept</span></a>
								<a href="{{url('feedback_accept/'.Crypt::encrypt($row->audit->id)."/2")}}"><span class="kt-badge kt-badge--warning kt-badge--inline">Reject</span></a>
							</td>
										
						</tr>
						
            		@endforeach
				@endif

				

        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('js')
{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}

<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>


<script>

	"use strict";
	var KTDatatablesBasicScrollable = function() {
	
		var initTable1 = function() {
			var table = $('#kt_table_1');
	
			// begin first table
			table.DataTable({
				scrollY: '50vh',
				scrollX: true,
				scrollCollapse: true,
				columnDefs: [],
				
				buttons: [
					{
						extend: 'excelHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'pdfHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'print',
						title: 'Rebuttal Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Rebuttal Report'
					}
				],
			});
		};
	
		var initTable2 = function() {
			var table = $('#kt_table_2');
	
			// begin second table
			table.DataTable({
				scrollY: '50vh',
				scrollX: true,
				scrollCollapse: true,
				dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
				<'row'<'col-sm-12'tr>>
				<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
				
				buttons: [
					@if(!Auth::user()->hasRole('agent'))
					{
						extend: 'excelHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'pdfHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'print',
						title: 'Rebuttal Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Rebuttal Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Rebuttal Report'
					}
					@endif
				],
				bSort : false,
				columnDefs: [],
			});
		};
	
		return {
	
			//main function to initiate the module
			init: function() {
				initTable1();
				initTable2();
			},
	
		};
	
	}();
	
	jQuery(document).ready(function() {
		KTDatatablesBasicScrollable.init();
	});
	jQuery(document).ready(function() {
		KTDatatablesExtensionButtons.init();
	});
	</script>

@endsection