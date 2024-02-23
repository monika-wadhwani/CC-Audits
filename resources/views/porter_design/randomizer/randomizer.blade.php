@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style>
    .LblHeading {
        font-weight: 600;
        margin-bottom: 10px;
    }
</style>
@section('main')
<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Randomizer Report :-Search / Filter</h3>
        </div>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="row">
            <div class="col-md-12">
            {!! Form::open(
                array(
                'route' => 'randomizer_report', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
                <div class=" mainTbl pe-1">
                    <div class="form-contentMet">
                        <div class="mb-3 w-50">
                            <label class="LblHeading" for="">Select Date Range</label>
                            <input type='text' class="form-control w-100" name="date_range" id="datepicker123" required="required"/>
                        </div>
                        <div class="mb-3 w-50">
                            <label class="LblHeading" for="">Process</label>
					        <input type="text" class="form-control" name="process" value="{{$all_process[0]->name}}" readonly="readonly">
                        </div>

                    </div>
				<a onclick="show()"><button type="submit" class="btn btn-primary float-right" >Create new random sample</button></a>
			</form>
                   
                    <div class="table-responsive w-100 mainTbl">
                        <h6>Samples List</h6>
                        <table class="table table-striped- table-bordered table-hover table-checkable">
			<thead style="text-align: center; background-color:#d0d6df" >
				<tr>
					<th>#</th>
					
					<th>Requested Date</th>
					<th>Process</th>
					<th>Total Randomizer Count
				</th>
				<th>Samples To Be Allocate
				</th>
					<th>Action</th>
					<th>QA  Allocation Report</th>
				
					
				</tr>
			</thead>
			<tbody style="text-align: center">
			@foreach($all_samples as $sample)
				<tr>
				{!! Form::open(
                array(
                'route' => 'final_output', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}
				<input type="hidden" value="{{$sample->id}}" name = "sample_id">
					<td>{{$index}}</td>
					<td>{{$sample->created_at}}</td>
					<td>{{$sample->name}}</td>
					
					<td style="text-align: center">
					<input type ="hidden" value ="{{count($sample->total_sample)}}" name = "total_count">{{count($sample->total_sample)}}
				</td>
				<td style="text-align: center">   
					@if(count($sample->final_sample) == 0)
					<input type="number" class="form-control" name = "required_count" min="0" >
					@else
					{{count($sample->final_sample)}}
					@endif
				</td>
					<td>	
						@if(count($sample->final_sample) == 0)
						<button type="submit" class="btn btn-primary">Assign Samples</button>
						
						@else
						<span style="color:grey"> Already Assign </span>
                        @endif	

					</td>
					<td>	
							
						@if($sample->auditor_qa_status == 1)
						<a href="{{url('view_samples/'.Crypt::encrypt($sample->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View Samples">
                        <i class="bi bi-eye"></i>
                        </a>
						
						@else
							@if(count($sample->final_sample) != 0)
							<span style="color:grey">Not Assign By TL</span>
							@endif	
                        @endif	
				

					
					</td>


				</form>
				</tr>

				<?php $index-- ?>
			@endforeach
			</tbody>	
		</table>
                    </div>
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
<script type="text/javascript">
var start_date = '';
var end_date = '';
$(function() {
  $("#datepicker123").daterangepicker({
    opens: 'right'
  }, function(start, end, label) {
      start_date = start.format('YYYY-MM-DD');
      end_date = end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
<script>
	function show(){
		document.getElementById("requested").style.display = "block";
	}
</script>
    @include('shared.form_js')

    @section('css')
    @endsection