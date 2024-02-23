@extends('layouts.app')

@section('sh-title')
Main Audited Pool
@endsection

@section('sh-detail')
Call
@endsection

@section('main')

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
		<!-- <div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-wrapper">
				<div class="kt-portlet__head-actions">
					<a href="{{url('skill/create')}}" class="btn btn-brand btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a>
				</div>
			</div>
		</div> -->
	</div>
	<div class="kt-portlet__body">

		<!--begin: Datatable -->
		{{ Form::open(array('url' => 'audited_list')) }}
			@csrf
			<div class="form-group row">
					<div class="col-lg-4">
						<a href="#"  class="btn btn-primary" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Select daterange" data-placement="right">
							<span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
							<span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date" >Aug 16</span> 
							<i class="flaticon2-calendar-1"></i>
						</a>
						<input type="hidden" name = "date_range" id="date_range">
						<button type="submit" class="btn btn-success">Get Data</button>
						
					</div>
				</div>
				<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
								<th title="Field #1">#</th>
                                <th title="Field #7">
                                Audit Date
								</th>
								<th title="Field #7">
                                Auditor Name	
								</th>
								<th title="Field #7">
								Audit Count
								</th>
								<th title="Field #7">
									Total Time Given
								</th>
								<th title="Field #7">
                                Auditor Time Spends in Audit
								</th>
								<th title="Field #2">
									Variance in Time
								</th>
								
						</tr>
					</thead>
					<tbody>
						@foreach($data as $row)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$row->created_at}}</td>
							<td>{{$row->auditor->name}}</td>
							<td>{{$row->audited_by_id->count()}}</td>
							<td>{{$row->audit_estimation_time_in_secs}}</td>
							<td>{{$row->auditor_time_spend_in_secs}}</td>
                            <td>0</td>
						</tr>
						@endforeach
					</tbody>
    			</table>

    			<!--end: Datatable -->
		{{ Form::close() }}
	</div>
</div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
<script>
	function date_range(){
		var date_range = document.getElementById("kt_dashboard_daterangepicker_date").innerHTML;
		var dates = document.getElementById("date_range").value = date_range;
		console.log(dates);

	}
	document.body.addEventListener('click', date_range, true); 
</script>
@endsection