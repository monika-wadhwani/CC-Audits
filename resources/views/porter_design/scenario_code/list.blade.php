@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">List</h4>
        <a href="{{ route('scenerio_tree.create') }}"><button class="btn btn-sm btn-primary">Bulk Uploads</button></a>
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">All List</h5>
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
