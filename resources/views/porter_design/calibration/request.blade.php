@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Calibration</h4>
        <!-- <a href="{{url('skill/create')}}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
						<i class="la la-plus"></i>
						New Record
					</a> -->
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">My Requests</h5>
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
							Title
						</th>
						<th title="Field #2">
							Due Date
						</th>
						<th title="Field #2">
							Client
						</th>
						<th title="Field #2">
							Process
						</th>
						<th title="Field #2">
							Qm Sheet
						</th>
						<th title="Field #2">
							Calibrators
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
                                                    {{$row->calibration->title}}
                                                </td>
                                                <td>
                                                    {{$row->calibration->due_date}}
                                                </td>
                                                <td>
                                                    {{$row->calibration->client->name}}
                                                </td>
                                                <td>
                                                    {{$row->calibration->process->name}}
                                                </td>
                                                <td>
                                                    {{$row->calibration->qm_sheet->name}} - V{{$row->calibration->qm_sheet->version}}
                                                </td>
                                                
                                                <td class="kt-font-bold">
                                                    <span class="kt-font-danger">{{$row->calibration->calibrator->where('status',1)->count()}}</span> / <span class="kt-font-success">{{$row->calibration->calibrator->count()}}</span>
                                                </td>
    
                        <td nowrap>
                            <div style="display: flex;">
    
                            @if($row->status==0)
                            <a href="{{url('calibrate/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Show Detail">
                                <i class="bi bi-list-stars"></i>
                            </a>	
                            @endif
                            <a href="{{url('calibration/'.Crypt::encrypt($row->calibration_id).'/result')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Show Detail">
                                <i class="bi bi-eye"></i>
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