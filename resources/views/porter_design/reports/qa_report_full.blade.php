@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">QA Report</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
    </div>
    <div class="tblM w-100 boxShaow px-3">
		<div class="titleBtm p-2">
			<h5 class="m-0 fs-14">List QA Performance Report</h5>
			<div class="d-flex mainSechBox">

				<a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
					<img src="/assets/design/img/filter-icon.png" width="100%">
				</a>
			</div>
		</div>
        <div class="table-responsive w-100 mainTbl">
        {!! Form::open(
                array(
                'route' => 'qa_performance', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
        
			<div class="kt-portlet__body">
				<div class="form-group row">
					<div class="d-flex align-items-center gap-3">
						<label for="start-date" class="mb-0 fw-bold text-primary">Start Date:</label>
						<input type="date" id="start-date" required name="start_date" class="form-control" value="">
						<img src="/assets/design/img/Icon awesome-calendar-alt.svg" class="calenderIcon" alt="calendaricon">
						<label for="end-date" class="mb-0 fw-bold text-primary">End Date:</label>
						<input type="date" id="end-date" required name="end_date" class="form-control" value="">
						<img src="/assets/design/img/Icon awesome-calendar-alt.svg" class="calenderIcon" alt="calendaricon">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div><br>
			</div>
        <div class="kt-portlet__foot">
        </div>
      </form>

	<div class="kt-portlet__body">

		@php($avg_time = array())
		@foreach($data as $row)
			
			@if($row->audits == 0)
				@php($avg_time[$row->id] =1254)
			@else
				@php($avg_time[$row->id] = $row->total_time / $row->audits)
			@endif
		@endforeach
		@php(asort($avg_time ))

		@php($avg_audit_day = array())
		@foreach($data as $row)
			
			@if($row->present_days == 0)
				@php($avg_audit_day[$row->id] =0)
			@else
				@php($avg_audit_day[$row->id] = $row->audits / $row->present_days)
			@endif
		@endforeach
		@php(arsort($avg_audit_day ))		
            <table class="table mb-0 datatables">
                <thead>
                <tr>
					<th title="Field #1">#</th>
					<th title="Field #2">
						Name
					</th>
					
					<th title="Field #2">
						Present Days
					</th>
					<th title="Field #2">
						Total Spent time 
					</th>
					<th title="Field #2">
						Total Audits
					</th>
					<th title="Field #2">
						Avg. Time/Audit
					</th>
					<th title="Field #2">
						Rank (Time/Audit)
					</th>
					
					<th title="Field #2">
						Rank(audits/present)
					</th>
					<th title="Field #2">
						Submited/day
					</th>
					<th title="Field #2">
						Average time Sepent/day
					</th>
					<th title="Field #2">
						Target
					</th >
					<th title="Field #2">
						Target Achieved
					</th>

				</tr>
                </thead>
                <tbody>
                @foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>
						{{$row->name}}
					</td>
					
					<td>
						{{$row->present_days}}
					</td>
					<td>
						@if(gettype($row->total_time) == 'NULL')
							
						@elseif($row->total_time <= 60)
							{{$row->total_time}} Min.
						@else
							{{ (int)($row->total_time / 60) }} Hours, {{ $row->total_time % 60 }} Min.
						@endif

					</td>
					
                    <td>
						{{$row->audits}}
					</td>
					<td>
						@if($row->audits == 0)
							
						@else
						{{ (int)($row->total_time / $row->audits)}} Min.
						@endif
					</td>
					<td>
						@php($i = 1)
						@foreach($avg_time as $key=>$value)
							@if($key == $row->id) 
								{{ $i }}
							@endif
							@php($i++)
						@endforeach
						
					</td>
					
					<td>

						@php($j = 1)
						@foreach($avg_audit_day as $key=>$value)
							@if($key == $row->id) 
								{{ $j }}
							@endif
							@php($j++)
						@endforeach
					</td>
					
					<td>
					@if($row->present_days != 0)
							{{ (int) ($row->audits/ $row->present_days)}}
						@endif
					</td>
					<td>
						@if($row->present_days != 0)
							@if($row->total_time/$row->present_days < 60)
								{{ (int) ($row->total_time/$row->present_days) }} Min.
							@else 
								{{ (int)(($row->total_time/$row->present_days) / 60) }} Hours, {{ ($row->total_time/$row->present_days) % 60 }} Min.
							@endif
						@endif
					</td>

					<td>
						{{ $row->qa_target }} 
					</td>
					<td>
						@if($row->qa_target != 0)
							{{ number_format((float)($row->audits/$row->qa_target) * 100, 2, '.', '')  }}%
						@endif
					</td>
            	</tr>
            @endforeach
        </tbody>
        </table>
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
<script src="/assets/app/custom/general/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
@endsection

@section('css')
<link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
{!! Html::script('assets/app/custom/general/crud/datatables/extensions/buttons.js')!!}
@endsection