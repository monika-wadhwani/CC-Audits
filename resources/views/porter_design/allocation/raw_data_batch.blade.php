@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Raw Data Batch</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">Raw Data Batch</h5>
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
							Partner
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Uploader
						</th>
						<th title="Field #2">
							Create At
						</th>
						<!--<th title="Field #7">
							Download File
						</th>-->
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
												<?=($row->partner) ? $row->partner->name : '-' ?>
											</td>
											<td>
												{{$row->process->name}}
											</td>
											<td>
												{{$row->uploader->name}}
											</td>
											<td>
												{{$row->created_at}}
											</td>
											<!--<td>
												<a href="{{Storage::url('raw_data_dump_file/').$row->file_name}}">Download Sheet</a>
											</td>-->
											

					<td nowrap>
						
                        <div style="display: flex;">
                        	{{Form::open([ 'method'  => 'delete', 'route' => [ 'allocationdel.destroy', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                        	<a href="{{url('allocation/pendingList/'.Crypt::encrypt($row->id))}}" target="_blank" title="View Pending Audit">
								<i class="bi bi-eye"></i>	
													</a>
                        	<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
                        		<i class="bi bi-trash"></i>
                        	</button>

                        </form>

                        @if($row->visiblity)

													<a href="{{url('batch/status/'.Crypt::encrypt($row->id).'/0')}}">
													<span class="kt-badge kt-badge--danger kt-badge--inline">Disable Now</span>
													</a>
												@else
													<a href="{{url('batch/status/'.Crypt::encrypt($row->id).'/1')}}">
													<span class="kt-badge kt-badge--success kt-badge--inline">Enable Now</span>
													</a>
												@endif

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