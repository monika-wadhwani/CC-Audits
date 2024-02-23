@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Training PKT</h4>

    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">List Training PKT</h5>
            <div class="d-flex mainSechBox">

                <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                    <img src="/assets/design/img/filter-icon.png" width="100%">
                </a>
            </div>
        </div>
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0 datatables">
                <thead>
                <tr>
						<th title="Field #1">#</th>
						<th title="Field #2">
							Name
						</th>
						<th title="Field #2">
							Month
						</th>
						
						<th title="Field #2">
							Count of Test
						</th>
						<th title="Field #2">
							Test Attendant
						</th>
						<th title="Field #2">
							Overall obtain Marks
						</th>
						<th title="Field #2">
							Out of Total Marks
						</th>
						<th title="Field #2">
							Avg. Score
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
						{{$row->pkt_month}}
					</td>
					
					<td>
						{{$row->count_of_test}}
					</td>
					<td>
						{{$row->test_attendent}}
					</td>
					<td>
						{{$row->overall_marks_obtain}}
					</td>
					<td>
						{{$row->out_of_total_marks}}
					</td>
					<td>
						{{$row->avg_score}}
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
@endsection

@section('css')

@endsection