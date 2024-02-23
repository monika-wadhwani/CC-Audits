@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <h4 class="fw-bold mb-1 boxTittle">QA Report</h4>
    </div>
    <div class="tblM w-100 boxShaow px-3">
        <div class="titleBtm p-2">
            <h5 class="m-0 fs-14">List QA Report</h5>
            <div class="d-flex mainSechBox">

                <a href="#" class="filterBtn ms-2" data-bs-toggle="modal" data-bs-target="#auditsModal">
                    <img src="/assets/design/img/filter-icon.png" width="100%">
                </a>
            </div>
        </div>

        <div class="table-responsive w-100 mainTbl">
            {!! Form::open(
            array(
            'route' => 'qa_report',
            'class' => 'kt-form',
            'role'=>'form',
            'data-toggle'=>"validator",
            'enctype'=>'multipart/form-data')
            ) !!}

            <div class="kt-portlet__body">
                <div class="row">
                    <div class="d-flex align-items-center gap-3">
                        <label for="" class="mb-0 fw-bold text-primary">Date:</label>
                        <div class="position-relative">
                            <input type="month" id="demo-1" name = "target_month"  class="form-control" value=""
                                placeholder="20-3-2012 to 12-2-3023">
                            <img src="/assets/design/img/Icon awesome-calendar-alt.svg" class="calenderIcon"
                                alt="calendaricon">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                </div>
            </div><br>
            <div class="kt-portlet__foot">
            </div>
            </form>

            <table class="table mb-0 datatables">
                <thead>
                    <tr>
                        <th title="Field #1">#</th>
                        <th title="Field #2">
                            Name
                        </th>
                        <th title="Field #2">
                            Email Id
                        </th>
                        <th title="Field #2">
                            Month
                        </th>
                        <th title="Field #2">
                            Target
                        </th>

                        <th title="Field #2">
                            Audit Completed
                        </th>

                        <th title="Field #2">
                            Target achieved %
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
                            {{$row->qa_email}}
                        </td>
                        <td>
                            {{$row->target_month}}
                        </td>

                        <td>
                            {{$row->qa_target}}
                        </td>
                        <td>
                            {{$row->count}}
                        </td>

                        <td>
                            {{ number_format((float)($row->count/$row->qa_target) * 100, 2, '.', '') }}%
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
@include('porter_design.shared.agent_dashbaord_js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/month_picker/monthpicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function () {
        $('.datatables').DataTable();
    });
</script>
<script>
    $('#demo-1').Monthpicker();

</script>
@endsection

@section('css')

@endsection