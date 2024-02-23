@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="fw-bold mb-1 boxTittle">{{$qm_sheet_data->name}}</h3>
        </div>
        <a href="/qm_sheet/{{Crypt::encrypt($qm_sheet_data->id)}}/add_parameter" target="_blank" class="btn btn-primary btn-bold">
            Create New Parameter
        </a>
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">Parameters</h5>
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
							Parameter
						</th>
						<th title="Field #2">
							Sub Parameter - Weightage
						</th>
						<th title="Field #2">
							Weightage
						</th>
						<th title="Field #2">
							Type
						</th>
						<th title="Field #7">
							Action
						</th>
				</tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{$row->parameter}}
                                                </td>
                                                <td>
                                                    <ol>
                                                    <?php $total_weightage=0; ?>
                                                    @foreach($row->qm_sheet_sub_parameter as $ksp=>$vsp)
                                                    <li>{{$vsp->sub_parameter}} - <strong>{{$vsp->weight}}</strong></li>
                                                    <?php $total_weightage += $vsp->weight;?>
                                                    @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    {{$total_weightage}}
                                                </td>
                                                <td>
                                                    {{($row->is_non_scoring)?"Non-Scoring":"Scoring"}}
                                                </td>
                                                
                        <td nowrap>
                            <div style="display: flex;">
                                {{Form::open([ 'method'  => 'delete', 'route' => [ 'delete_parameter', Crypt::encrypt($row->id) ],'onsubmit'=>"delete_confirm()"])}}
                                <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <a href="{{url('parameter/'.Crypt::encrypt($row->id).'/edit')}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
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