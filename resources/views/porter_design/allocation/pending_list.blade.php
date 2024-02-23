@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')
    <div class="container-fluid">
        <div class="titlTops cardBox my-2">
            <h4 class="fw-bold mb-1 boxTittle">Pending Call List</h4>
            <!-- <a href="{{ url('skill/create') }}" class="btn btn-brand btn-primary btn-elevate btn-icon-sm">
          <i class="la la-plus"></i>
          New Record
         </a> -->
        </div>
        <div class="tblM w-100 boxShaow px-3">
            <div class="titleBtm p-2">
                <h5 class="m-0 fs-14">Pending Call List</h5>
                <div class="d-flex mainSechBox">

                    <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                        <img src="/assets/design/img/filter-icon.png" width="100%">
                    </a>
                </div>
            </div>
           

            <div class="table-responsive w-100 mainTbl">
                <div class="titleBtm p-2">
                    <form method="post" action="{{ url('allocation/reassign_search') }}" id="form">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{ $batch_id }}">
                        <div class="row">
                            <div class="col-md-5 mb-2">
                                <input class="form-control" type="text" name="search" id="search" placeholder="Search">
                            </div>
                            <div class="col-md-4 mb-2">
                                <button class="btn btn-md btn-primary form-control" type> Search</button>
    
    
                            </div>
                        </div>
                    </form>
                </div>
                <form method="post" action="{{url('allocation/reassign_multiple')}}" id="form">
                    @csrf
                    <input type="hidden" name="batch_id" value = "{{$batch_id}}">
                    <hr>	
                
                <br>
                <table class="table mb-0 datatables">
                    <thead>
                        <th><input type="checkbox" onclick="secall()" id="check_0" name="raw_data[0]" value="1"><label for="check_0">All</label></th>
						<th title="Field #1">#</th>
						
						<th title="Field #4">
							Call Id
						</th>
						<th title="Field #4">
							Audit Date
						</th>
						<th title="Field #5">
							Allocated QA
						</th>	
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                        <td><input type="checkbox" class="checkSec" id="check_<?=$row->id; ?>"  name="raw_data[<?=$row->id; ?>]" value="1" ><label for="check_<?php echo $row->id; ?>"></label></td>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$row->call_id}} </td>					
                            <td>{{$row->dump_date}} </td>	
                            <td>
                                @foreach($allQa as $qa)
                                <?php if($qa->user_id == $row->qa_id) { echo $qa->getUser->name; } ?> 
                                @endforeach  
                            </td>			
                            
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-2 mb-2">
                        <select name="qa_id"  class="form-control">
                            <option value="0">Allocate to QA</option>
                            @foreach($allQa as $qa)
                            <option value="{{$qa->user_id}}"> {{ $qa->getUser->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-2 mb-2">
                        <button class="btn btn-md btn-primary form-control" type="submit"> Allocate</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datatables').DataTable();
        });
    </script>
@endsection

@section('css')
@endsection
