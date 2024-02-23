@extends('layouts.app')

@section('sh-title')
Report
@endsection

@section('sh-detail')
Raw dump with Audit data
@endsection

@section('main')
<div class="row">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Filter
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<form>
					<raw-dump-filter-component></raw-dump-filter-component>
					<button type="Submit" class="btn btn-success">Submit</button>
				</form>
				<a onclick="show()"><button class="btn btn-primary float-right"> Show Requested Reports</button></a>
				<p style="color:red">{{$msg}}</p>
			</div>
		</div>
	</div>
</div>
<div class="kt-portlet kt-portlet--mobile" id="requested" style="display:none">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Requested Reports
			</h3><br>

		</div>
	</div>
	<div class="kt-portlet__body">
		<p> Reports requested in morning will be available at 2PM.</p>
		<p> Reports requested after 1PM will be availabe next day morning.</p>

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable">
			<thead>
				<tr>
					<th>#</th>

					<th>Requested Date</th>
					<th>Process</th>
					<th>Date Range</th>
					<th>Download</th>
				</tr>
			</thead>
			<tbody>
				@foreach($old_reports as $report)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$report->created_at}}</td>
					<td>{{$report->name}}</td>
					<td>{{$report->filter_start_date}} to {{$report->filter_end_date}}</td>
					<td>@if($report->file_location)
						<?php
						$path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $report->file_location);
						$url = Storage::disk('s3')->temporaryUrl(
							$path_name,
							now()->addMinutes(3000) //Minutes for which the signature will stay valid
						);
						?>
						<a href="{{$url}}">Download Report</a>

						@else
						Pending
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Details
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
			<thead>

				<tr>
					<th>#</th>
					@if(Auth::user()->hasRole('client')||
					Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') ||
					Auth::user()->hasRole('partner-quality-head') ||
					Auth::user()->hasRole('partner-admin'))
					<th>Auditor Name</th>
					@else
					<th>Auditor Name</th>
					@endif

					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_4 }}
						@else
						Audit Date
						@endif
					</th>
					<th>

						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_3 }}
						@else
						Partner
						@endif

					</th>
					<th>
						Location

					</th>
					<th>Call Id</th>
					<th>Call File</th>
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_5 }}
						@else
						Agent Name
						@endif

					</th>

					@if(Auth::user()->parent_client == 14)
					<th>
						Agent Feedback
					</th>
					@endif

					<th>Emp. ID</th>
					<th>Doj</th>
					<th>LOB</th>
					<th>
						Language

					</th>
					<th>
						Language For QA

					</th>
					<th>Case Id</th>
					@if(Auth::user()->hasRole('client')||
					Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') ||
					Auth::user()->hasRole('partner-quality-head') ||
					Auth::user()->hasRole('partner-admin'))

					@else
					<th>Auditor's Total Spend Time</th>
					@endif

					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_19 }}
						@else
						Call Time
						@endif

					</th>
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_20 }}
						@else
						Call Duration
						@endif

					</th>
					<th>Call Type</th>
					<!-- <th>
						@if (isset($labels->qm_sheet_id))
							{{ $labels->info_9 }}							
						@else 
							Call Sub Type
						@endif
					</th> -->
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_10 }}
						@else
						Disposition
						@endif
					</th>
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_8 }}
						@else
						Compaign Name
						@endif
					</th>
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_11 }}
						@else
						Customer Name
						@endif
					</th>
					<th>
						@if (isset($labels->qm_sheet_id))
						{{ $labels->info_12 }}
						@else
						Customer Phone
						@endif
					</th>
					<!-- <th>Audit Date</th>
				<th>Partner</th>
				<th>Location</th>
				<th>Call Id</th>
				<th>Agent Name</th>
				<th>Emp. ID</th>
				<th>Doj</th>
				<th>LOB</th>
				<th>Language</th>
				<th>Case Id</th>
				<th>Call Time</th>
				<th>Call Duration</th>
				<th>Call Type</th>
				<th>Call Sub Type</th>
				<th>Disposition</th>
				<th>Compaign Name</th>
				<th>Customer Name</th>
				<th>Customer Phone</th> -->
					<th>Reference Number</th>
					<th>QRC</th>
					<th>Overall Summary</th>
					<th>With Fatal Score</th>
					<th>Without Fatal Score</th>
					<th>Brand Name</th>
					<th>Circle</th>
					<th>Info 1</th>
					<th>Info 2</th>
					<th>Info 3</th>
					<th>Info 4</th>
					<th>Info 5</th>
					<th>CRN No./Order ID</th>
					<th>Caller ID:</th>
					<th>
						Vehicle Type
					</th>
					<th>Caller Type</th>
					<th>Order Stage</th>
					<th>Audit Type</th>
					<th>Issues</th>
					<th>Sub Issues</th>
					<th>
						Scanerio Code
					</th>
					<th>Scanerio Codes</th>
					<th>Error Reason Type</th>
					<th>Error Reasons</th>
					<th>
						Error Code
					</th>


					@foreach($repeater_param_data as $kk=>$vv)
					@foreach($vv as $kkb=>$vvb)
					<th>{{$vvb['name']}}</th>
					<th>Scored</th>
					<th>Scorable</th>
					<th>Reason Type</th>
					<th>Reason</th>
					<th>Remark</th>
					@endforeach
					@endforeach
					<th>Total Scored</th>
					<th>Total Scorable</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $kk=>$vv)
				<tr>
					<td>{{$loop->iteration}}</td>
					@if(Auth::user()->hasRole('client')||
					Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') ||
					Auth::user()->hasRole('partner-quality-head') ||
					Auth::user()->hasRole('partner-admin'))
					<td>{{$vv['auditor']}}</td>
					@else
					<td>{{$vv['auditor']}}</td>
					@endif
					<td>{{$vv['audit_date']}}</td>
					<td>{{$vv['partner']}}</td>
					<td>{{$vv['location']}}</td>
					<td>{{$vv['temp_raw_data']->call_id}}</td>
					<td>{{$vv['good_bad_call_file']}}</td>
					<td>{{$vv['temp_raw_data']->agent_name}}</td>
					@if(Auth::user()->parent_client == 14)
					<td>{{$vv['agent_feedback']}}</td>
					@endif
					<td>{{$vv['temp_raw_data']->emp_id}}</td>
					<td>{{$vv['temp_raw_data']->doj}}</td>
					<td>{{$vv['temp_raw_data']->lob}}</td>
					<td>{{$vv['language_2']}}</td>
					<td>{{$vv['language_for_qa']}}</td>
					<td>{{$vv['case_id']}}</td>
					@if(Auth::user()->hasRole('client')||
					Auth::user()->hasRole('partner-training-head') || Auth::user()->hasRole('partner-operation-head') ||
					Auth::user()->hasRole('partner-quality-head') ||
					Auth::user()->hasRole('partner-admin'))

					@else

					<td>
						<?php
						$h = intval($vv['auditor_time_spend'] / 3600);

						$vv['auditor_time_spend'] = $vv['auditor_time_spend'] - ($h * 3600);
						$m = intval($vv['auditor_time_spend'] / 60);
						$s = $vv['auditor_time_spend'] - ($m * 60);
						?>
						@if(!isset($h))
						{{$h}} Hours, {{$m}} Minutes, {{$s}} Secs.

						@else
						{{$m}} Minutes, {{$s}} Secs.
						@endif

					</td>
					@endif
					<td>{{$vv['temp_raw_data']->call_time}}</td>
					<td>{{$vv['temp_raw_data']->call_duration}}</td>
					<td>{{$vv['call_types']}}</td>
					<!-- <td>{{$vv['temp_raw_data']->call_sub_type}}</td> -->
					<td>{{$vv['temp_raw_data']->disposition}}</td>
					<td>{{$vv['temp_raw_data']->campaign_name}}</td>
					<td>{{$vv['temp_raw_data']->customer_name}}</td>
					<td>{{$vv['temp_raw_data']->phone_number}}</td>
					<td>{{$vv['refrence_number']}}</td>
					<td>{{$vv['qrc_2']}}</td>
					<td>{{$vv['overall_summary']}}</td>
					<td>{{$vv['with_fatal_score_per']}}</td>
					<td>{{$vv['without_fatal_score']}}</td>
					<td>{{$vv['temp_raw_data']->brand_name}}</td>
					<td>{{$vv['temp_raw_data']->circle}}</td>
					<td>{{$vv['temp_raw_data']->info_1}}</td>
					<td>{{$vv['temp_raw_data']->info_2}}</td>
					<td>{{$vv['temp_raw_data']->info_3}}</td>
					<td>{{$vv['temp_raw_data']->info_4}}</td>
					<td>{{$vv['temp_raw_data']->info_5}}</td>
					<td>{{$vv['order_id']}}</td>
					<td>{{$vv['caller_id']}}</td>
					<td>{{$vv['vehicle_type']}}</td>
					<td>{{$vv['caller_type']}}</td>
					<td>{{$vv['order_stage']}}</td>
					<td>{{$vv['audit_type']}}</td>
					<td>{{$vv['issues']}}</td>
					<td>{{$vv['sub_issues']}}</td>

					<td>{{$vv['scanerio']}}</td>
					<td>{{$vv['scanerio_codes']}}</td>
					<td>{{$vv['error_reason_type']}}</td>
					<td>{{$vv['error_code_reasons']}}</td>
					<td>{{$vv['new_error_code']}}</td>


					<!-- <td>{{$vv['new_error_code']}}</td> -->

					@php($total_scored = 0)
					@php($total_scorable = 0)
					@php($is_critical = 0)
					@foreach($vv['audit'] as $kk=>$vv)
					@foreach($vv as $kkb=>$vvb)
					<?php if ($vvb['observation'] == 'Critical') {
						$is_critical = 1;
					} ?>
					<td>{{$vvb['observation']}}</td>
					<td>{{$vvb['scored']}}</td>
					<td>{{$vvb['scorable']}}</td>

					<td>{{$vvb['reason_type']}}</td>
					<?php
					$string = str_replace(' ', '-', $vvb['reason']); // Replaces all spaces with hyphens.
					$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
					$string = preg_replace('/-+/', ' ', $string);
					?>

					<td>{{strip_tags($string)}}</td>



					<?php
					$string = str_replace(' ', '-', $vvb['remark']); // Replaces all spaces with hyphens.
					$string = preg_replace('/[^,"":().A-Za-z0-9-\/-]/', '', $string);
					$string = preg_replace('/-+/', ' ', $string);
					?>

					<td>{{strip_tags($string)}}</td>



					@php($total_scored += $vvb['scored'])
					@php($total_scorable += $vvb['scorable'])
					@endforeach
					@endforeach
					<td>
						<?php if ($is_critical == 1) {
							echo 0;
						} else {
							if ($total_scored >= $total_scorable) {
								echo $total_scorable;
							} else {
								echo $total_scored;
							}


						} ?>
					</td>
					<td>
						<?php if ($total_scorable > 100) {
							echo 100;
						} else {
							echo $total_scorable;
						} ?>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('js')

{!! Html::script('assets/vendors/custom/datatables/datatables.bundle.js')!!}

<script>

	"use strict";
	var KTDatatablesBasicScrollable = function () {

		var initTable1 = function () {
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
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'pdfHtml5',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'print',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Raw Dump With Audit Report'
					}
				],
			});
		};

		var initTable2 = function () {
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
					{
						extend: 'excelHtml5',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'pdfHtml5',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'print',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'copyHtml5',
						title: 'Raw Dump With Audit Report'
					},
					{
						extend: 'csvHtml5',
						title: 'Raw Dump With Audit Report'
					}
				],
				bSort: false,
				columnDefs: [],
			});
		};

		return {

			//main function to initiate the module
			init: function () {
				initTable1();
				initTable2();
			},

		};

	}();

	jQuery(document).ready(function () {
		KTDatatablesBasicScrollable.init();
	});
	jQuery(document).ready(function () {
		KTDatatablesExtensionButtons.init();
	});
</script>
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
@endsection
@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
<script>
	function show() {
		document.getElementById("requested").style.display = "block";
	}
</script>