@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">My Submited Audits</h4>
        <p class="text-black-50 m-0">Here the Main Pool</p>
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">All Audits</h5>
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
								<th title="Field #7">
									Audit Date
								</th>
								<th title="Field #7">
									Call ID
								</th>
								<th title="Field #7">
									Case ID
								</th>
								<th title="Field #7">
									CRN No./Order id
								</th>
								<th title="Field #2">
									Agent Name
								</th>
								<th title="Field #7">
									Score With FATAL
								</th>
								<th title="Field #7">
									Score Without FATAL
								</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$row->created_at}}</td>
							<td>{{$row->raw_data->call_id}}</td>
							<td>{{$row->case_id}}</td>
							<td>{{$row->order_id}}</td>
							<td>{{$row->raw_data->agent_name}}</td>
							<td>{{($row->is_critical==1)?0:$row->overall_score."%"}}</td>
							<td>{{$row->overall_score}} %</td>
						</tr>
						@endforeach
                  
                </tbody>
            </table>
        </div>
        {{-- <div class="paginationBox d-flex align-items-center py-3 justify-content-end">
            <span>Showing 10 out of {{30}} results</span>
            <div class="pgiNext">
              <a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <a href="#" class="active">1</a>
              <a href="#" class="">2</a>
              <a href="#" class="">3</a>
              <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>
       </div> --}}
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
