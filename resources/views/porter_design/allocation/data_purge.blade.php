@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">QM - Sheet</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">Purged Data</h5>
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
							No of Purged Data
						</th>
						<th title="Field #2">
							Time When Data Purged
						</th>
				</tr>
                </thead>
                <tbody>
                @foreach($data as $row)
				<tr>
					<td>{{$loop->iteration}}</td>

					<td>
						{{$row->no_of_record}}
					</td>
					<td>
						{{$row->created_at}}
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