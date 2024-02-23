@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">Randomizer Report</h4>

    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">Report :- Randomizer Report</h5>
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
                        <th title="Field #2">SR Number</th>
                        <th title="Field #2">Agent ID</th>
                        <th title="Field #3">Call ID</th>
                        <th title="Field #4">Date of Interaction</th>
                        <th title="Field #4">Call Duration</th>
                        <th title="Field #2">Location</th>
                        <th title="Field #2">Language</th>
                        <th title="Field #5">Call Type</th>
                        <th title="Field #5">Disposition</th>
                        <th title="Field #6">Sub Disposition</th>
                        <th title="Field #2">Campaign Name</th>
                        <th title="Field #8">Hangup Details</th>
                        <th title="Field #2">LOB</th>
                        <th title="Field #2">TL</th>
                        <th title="Field #2">DOJ</th>
                        <th title="Field #7">Agent Name</th>
                        <th title="Field #2">Customer Name</th>
                        <th title="Field #2">QA System Registered Email</th>
                        <th title="Field #2">Brand Name</th>
                        <th title="Field #2">Circle</th>
                        <th title="Field #8">Final Tagging</th>
                        <th title="Field #2">Info 2</th>
                        <th title="Field #2">Info 3</th>
                        <th title="Field #2">Info 4</th>
                        <th title="Field #2">Info 5</th>
                    
                    </tr>
                </thead>
                <tbody>

                    @foreach($data as $kk=>$vv)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$vv->phone_number}}</td>
                        <td>{{$vv->agent_name}}</td>
                        <td>{{$vv->call_id}}</td>
                        <td>{{$vv->call_time}}</td>
                        <td>{{$vv->call_duration}}</td>
                        <td>{{$vv->location}}</td>
                        <td>{{$vv->language}}</td>
                        <td>{{$vv->call_type}}</td>
                        <td>{{$vv->disposition}}</td>
                        <td>{{$vv->call_sub_type}}</td>
                        <td>{{$vv->campaign_name}}</td>
                        <td>{{$vv->hangup_details}}</td>
                        <td>{{$vv->lob}}</td>
                        <td>{{$vv->tl}}</td>
                        <td>{{$vv->doj}}</td>
                        <td>{{$vv->emp_id}}</td>
                        <td>{{$vv->customer_name}}</td>
                        <td>{{$vv->emp_id}}</td>
                        <td>{{$vv->brand_name}}</td>
                        <td>{{$vv->circle}}</td>
                        <td>{{$vv->info_1}}</td>
                        <td>{{$vv->info_2}}</td>
                        <td>{{$vv->info_3}}</td>
                        <td>{{$vv->info_4}}</td>
                        <td>{{$vv->info_5}}</td>
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
    var start_date = '';
var end_date = '';
$(function() {
  $("#datepicker123").daterangepicker({
    opens: 'right'
  }, function(start, end, label) {
      start_date = start.format('YYYY-MM-DD');
      end_date = end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>

@endsection

@section('css')

@endsection