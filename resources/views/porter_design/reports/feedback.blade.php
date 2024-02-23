@extends('porter_design.layouts.app')
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Feedback Notification</h4>
        <p class="text-black-50 m-0">Here the list of feedback</p>
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">All Feedback Notification</h5>
            <div class="d-flex mainSechBox">

                <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                    <img src="assets/design/img/filter-icon.png" width="100%">
                </a>
            </div>
        </div>
        <div class="table-responsive w-100 mainTbl">
            <table class="table mb-0 datatables">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Feedback From</th>
                        <th>Call Id</th>
                        <th>Process Name</th>
                        <th>Score Without Fatal</th>
                        @if(Auth::user()->hasRole('agent'))
                        <th>Time</th>
                        @endif
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($all_feedbacks as $item)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$item->auditor->name}}</td>
                        <td>{{$item->raw_data->call_id}}</td>
                        <td>{{$item->process->name}}</td>
                        <td>
                            <div class="tblProgress">
                                <div class="progress" role="progressbar" aria-label="Example with label"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: {{$item->overall_score}}%"></div>
                                </div>
                                <span>{{$item->overall_score}}%</span>
                            </div>
                        </td>
                        @php 
                            $expire = compair_dates($item->rebuttal_tat,date('Y-m-d H:i:s'));
                        @endphp
                        @if(Auth::user()->hasRole('agent'))
                        <td>
                            @if($expire == 1)
                            <span class="badgeRed badged">Expired</span>
                            @else
                            <span class="badgeGreen badged">{{time_difference($item->rebuttal_tat,date('Y-m-d H:i:s'))}} Hr Left </span>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if(Auth::user()->hasRole('agent'))
                            @if($expire == 0)
                            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#acceptedMain">Accepted</button>
                            @endif
                            @endif
                            <a href="{{url('partner/all_agent_audit_detail/'.Crypt::encrypt($item->id))}}">
                            <button class="btn btn-primary">Review</button></a> 
                            <!-- <a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($item->id))}}">
                            <button class="btn btn-primary">Review</button></a>  -->
                        </td>
                    </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                  
                </tbody>
            </table>
        
        </div>
    </div>
    
</div>

@endsection

@section('js')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.datatables').DataTable();
        });
    </script>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection
