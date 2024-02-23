@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Rebuttal</h4>
      
    </div>
    <div class="tblM w-100 cardBox px-3">
        <div class="titleBtm flex-wrap align-items-center py-0">
            <h5 class="m-0 fs-14 ">Rebuttals</h5>
            
        </div>
        <hr class="my-2">
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0 datatables">
                <thead>
                   
                    <tr>
						<th title="Field #1">#</th>
						<th title="Field #1">Status</th>
						<th title="Field #2">
							Call Id
						</th>
						<th title="Field #3">
							Parameter
						</th>
						<th title="Field #3">
							Sub Parameter
						</th>
						<th title="Field #4">
							Remark
						</th>
						<th title="Field #4">
							Raised At
						</th>
						@if(Auth::user()->hasRole('qtl'))
						<th title="Field #4">
						Details
						</th>
						@else
						<th title="Field #4">
						Status
						</th>
						@endif
						
				    </tr>
                </thead>
                <tbody>

                    @foreach($data as $row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @if ($row->re_rebuttal_id == null)
                                Rebuttal
                            @else
                                Re-Rebuttal
                            @endif
                        </td>
                        <td>{{$row->raw_data->call_id}}</td>
                        <td>{{$row->parameter->parameter}}</td>
                        <td>{{$row->sub_parameter->sub_parameter}}</td>
                        <td>{{$row->remark}}</td>
                        <td>{{$row->created_at}}</td>
                        @if(Auth::user()->hasRole('qtl'))
                        <td nowrap>
                            <div style="display: flex;">
                            <a href="{{url('rebuttal_status/'.Crypt::encrypt($row->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Update Status">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>

                    </td>
                    @else
                    <td>
                        @if($row->status == 1)
                        Accepted
                        @elseif($row->status == 2)
                        Rejected
                        @else($row->status == 0)
                        Raised
                        @endif
                    </td>
                    @endif
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
