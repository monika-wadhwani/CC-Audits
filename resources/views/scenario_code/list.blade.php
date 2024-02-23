@extends('layouts.app')

@section('sh-title')
Scenario Code
@endsection

@section('sh-detail')
Scenario Code <a href="{{ route('scenerio_tree.create') }}"><button class="btn btn-primary">Bulk Upload</button></a>
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
							Caller
						</th>
						<th title="Field #7">
							Order Stage
						</th>
                        <th title="Field #7">
							Issue
						</th>
                        <th title="Field #7">
							Sub Issues
						</th>
                        <th title="Field #7">
                            Scenario
						</th>
                        <th title="Field #7">
                            Scenerio Code
						</th>
                        

				</tr>
			</thead>
			<tbody>
				@foreach($scenario as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
                    <td>
                        {{$row->caller}}
                    </td>
					<td>
                        {{$row->order_stage}}
                    </td>
                    <td>
                        {{$row->issue}}
                    </td>
                    <td>
                        {{$row->sub_issues}}
                    </td>
                    <td>
                        {{$row->scenario}}
                    </td>
                    <td>
                        {{$row->scenerio_code}}
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