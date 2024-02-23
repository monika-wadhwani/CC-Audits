@extends('layouts.app')

@section('sh-title')
Error Codes
@endsection

@section('sh-detail')
Reason & Reason Types
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
		<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
			<thead>
				<tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Error Reason Type
						</th>
						<th title="Field #7">
							Error Reason
						</th>
                        <th title="Field #7">
							Error Code
						</th>
				</tr>
			</thead>
			<tbody>
				@foreach($error_codes as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
											<td>
												{{$row->error_reason_types}}
											</td>
					<td>
                      
                    {{$row->error_reasons}}
                </td>
                <td>
                      
                {{$row->error_codes}}
                </td>
            	</tr>
            @endforeach

        </tbody>
    </table>

    <!--end: Datatable -->
</div>
</div>
@endsection
@section('css')
@include('shared.table_css');
@endsection
@section('js')
@include('shared.table_js');
@endsection