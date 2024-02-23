@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Reason</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
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
							Client
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Reason Type
						</th>
						<th title="Field #2">
							Lable
						</th>
						<th title="Field #2">
							Total Reasons
						</th>
						<th title="Field #2">
							Details
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
						{{$row->client->name}}
					</td>
					<td>
						{{$row->process->name}}
					</td>
					<td>
						{{$row->name}}
					</td>
					<td>
						{{$row->label}}
					</td>
					<td>
						{{$row->reasons->count()}}
					</td>
					<td>
						{{$row->detail}}
					</td>
					<td nowrap>
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'reason.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<!-- <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        		<i class="la la-trash"></i>
                        	</button> -->
                        </form>
                        <a href="{{url('reason/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
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