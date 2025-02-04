@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
<div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">Cluster</h3>
        </div>
        <a href="{{url('cluster/create')}}"><button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
		New Record</button></a>
    </div>
	<div class="tblM w-100 boxShaow px-3">
		<div class="titleBtm p-2">
			<h5 class="m-0 fs-14">Master</h5>
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
							Cluster Name
						</th>
						<th title="Field #2">
							Cluster Detail
						</th>
						<th title="Field #2">
							Total Circle
						</th>
						<th title="Field #7">
							Actions
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
							{{$row->detail}}
						</td>
						<td>
							{{$row->circle->count()}}
						</td>
						<td nowrap>
							<div style="display: flex;">
								{{Form::open([ 'method' => 'delete', 'route' => [ 'cluster.destroy',
								Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
								<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
									<i class="bi bi-trash3"></i>
								</button>
								</form>
								<a href="{{url('cluster/'.Crypt::encrypt($row->id).'/edit')}}"
									class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
									<i class="bi bi-pencil"></i>
								</a>
							</div>
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