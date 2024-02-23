@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Rebuttal</h4>
        <p class="text-black-50 m-0">Welcome Back!</p>
    </div>
    <div class="tblM w-100 cardBox px-3">
        @if(Auth::user()->hasRole('agent-tl'))
        <div class="titleBtm flex-wrap align-items-center py-0">
            <h5 class="m-0 fs-14 ">Rebuttals</h5>
            <div class="d-flex mainSechBox">
                <button type="button" class="btn btn-primary me-2 d-flex align-items-center px-4"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> <img
                        src="/assets/design/img/status-icon.png" class="me-2" width="17" alt="img"> Select Status</button>
                <ul class="dropdown-menu py-2" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="javacript:void(0);">Valid</a></li>
                    <li><a class="dropdown-item" href="#">Invalid</a></li>
                    <li><a class="dropdown-item" href="#">Auto Close</a></li>
                    <li><a class="dropdown-item" href="#">Accepted</a></li>
                    <li><a class="dropdown-item" href="#">Rejected</a></li>
                    <li><a class="dropdown-item" href="#">Auto Accepted</a></li>

                </ul>
                <button type="button" class="btn btn-primary px-5">Save</button>
            </div>
        </div>
        @endif
        <hr class="my-2">
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0">
                <thead>
                   
                    <tr>
                        <th>Sr. No</th>
                        <th>Time Left (Hr:Min)</th>
                        <th>Call Id</th>
                        <th>Lob</th>
                        <th>Audit Date</th>
                        <th>Agent Name</th>
                        <th>Caller Name</th>
                        <th>Caller contact number</th>
                        <th>Parameter</th>
					    <th>Sub Parameter</th>
                        <th>Status</th>
                        <th>Expand and Track</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($data as $kk=>$vv)
                   
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="clockRebuttal"><span>{{ $vv['remaningTime'][0] }}</span>:<span>{{ $vv['remaningTime'][1] }}</span></td>
                        <td>{{$vv['call_id']}}</td>
                        <td>{{$vv['lob']}}</td>
                        <td>{{$vv['audit_date']}}</td>
                        <td>{{$vv['agent_name']}}</td>
                        <td>{{$vv['customer_name']}}</td>
                        <td>{{$vv['phone_number']}}</td>
                        <td>{{$vv['parameter']}}</td>
                        <td><span title="{{$vv['sub_parameter']}}">{{substr($vv['sub_parameter'],0,30)}}</span></td>
                        <td><span class="badgeGreen badged">Raised</span></td>
                        <td>
                            <a href="{{ route('rebuttal_treking', encrypt($vv['audit_id'])) }}" class="btnactionrebuttal">
                                <img src="/assets/design/img/audit-action.svg" alt="">
                            </a>
                        </td>
                    </tr>
                    @endforeach

                   
                    
                </tbody>
            </table>
        </div>
        <!-- <div class="paginationBox d-flex align-items-center py-3 justify-content-end">
            <span>Showing 10 out of 30 results</span>
            <div class="pgiNext">
              <a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <a href="#" class="active">1</a>
              <a href="#" class="">2</a>
              <a href="#" class="">3</a>
              <a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>
       </div> -->
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
