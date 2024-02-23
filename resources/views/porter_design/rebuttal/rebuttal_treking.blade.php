@extends('porter_design.layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
@section('main')

<div class="container-fluid">
    <div
        class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
        <div class>
            <h3 class="fw-bold mb-1 boxTittle">Rebuttal</h3>
            <p class="text-black-50 m-0">Welcome Back!</p>
        </div>
    </div>
    <div class="cardBox my-2 px-3">
        <div class="titleBtm py-2 border-bottom mb-2">
            <h5 class="m-0 fs-14">Plan Of Action</h5>
        </div>
        <div class="row">
            <div class="col-md-5">
                <h6 class="m-0 py-3 fs-14 fw-bold">Plan Of Action</h6>
                <div class="fourTimes pb-4 pt-3 border-bottom">
                    <div class="clockRebuttal d-flex">
                        <div class="clockText1"><span>{{ $remaningTime[0] }}</span></div>:
                        <div class="clockText2"><span>{{ $remaningTime[1] }}</span></div>
                    </div>
                </div>
                <div class="planContentIJ mt-4">
                    <div class="callsUI">
                        <div class="w-100">
                            <p class="text-primary mb-1">Call ID</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ $auditData->raw_data->call_id }}</p>
                        </div>
                        <div class="w-100">
                            <p class="text-primary mb-1">Client Name</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ $auditData->client->name }}</p>
                        </div>
                    </div>
                    <div class="callsUI">
                        <div class="w-100">
                            <p class="text-primary mb-1">Audit Date</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ date("Y-m-d", strtotime($auditData->audit_date)) }} | {{ date("H:i:s", strtotime($auditData->audit_date)) }}</p>
                        </div>
                        <div class="w-100">
                            <p class="text-primary mb-1">Agent Name</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ $auditData->raw_data->agent_details->name }}</p>
                        </div>
                    </div>
                    <div class="callsUI">
                        <div class="w-100">
                            <p class="text-primary mb-1">Customer Name</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ $auditData->raw_data->customer_name }}</p>
                        </div>
                        <div class="w-100">
                            <p class="text-primary mb-1">Customer contact number</p>
                            <p class="mb-0 fs-14 fw-semibold">{{ $auditData->raw_data->phone_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="trackingArea">
                    <h6 class="m-0 py-3 fs-14 fw-bold">Track Feedback</h6>
                    <div class="trackingArea321">
                        <lottie-player src="/assets/design/img/tracking-truck.json"
                            background="transparent" speed="1"
                            style="width: 80px; height: 57px;" loop
                            autoplay></lottie-player>
                    </div>
                    <div class="progress progressRebuttal"
                        role="progressbar"
                        aria-label="Example 20px high"
                        aria-valuenow="25" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar" style="width: 68%"></div>
                    </div>
                    <div
                        class="trackFeedBack d-flex justify-content-between mb-5">
                        <div class="trackFeedbackside">
                            <span></span>
                            <h6 class="fs-14 m-0 text-success">80
                                Hrs</h6>
                            <p class="mb-3 text-secondary">Assigned
                                to</p>
                            <h5 class="fs-15 fw-bold">Sam Mathews
                                (Team Leader)</h5>
                        </div>
                        <div class="trackFeedbackside">
                            <span></span>
                            <h6
                                class="fs-14 m-0 text-danger-emphasis">40
                                Hrs</h6>
                            <p class="mb-3 text-secondary">Assigned
                                to</p>
                            <h5 class="fs-15 fw-bold">Sam Mathews
                                (Team Leader)</h5>
                        </div>
                        <div class="trackFeedbackside active">
                            <span></span>
                            <h6 class="fs-14 m-0 text-warning">13
                                Hrs</h6>
                            <p class="mb-3 text-secondary">Assigned
                                to</p>
                            <h5 class="fs-15 fw-bold">Sam Mathews
                                (Team Leader)</h5>
                        </div>
                        <div class="trackFeedbackside deactivate">
                            <span></span>
                            <h6 class="fs-14 m-0 text-danger">10 Hrs</h6>
                            <p class="mb-3 text-secondary">System
                                Auto Close</p>
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="rebutlList">
                        <h6 class="m-0 py-3 fs-14 fw-bold">Rebuttal List</h6>
                        <div
                            class="table-responsive w-100 mainTbl tableOfWise">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-primary">Action</th>
                                        <th class="text-primary">Select One</th>
                                        <th class="text-primary">Behavior</th>
                                        <th class="text-primary">Parameter</th>
                                        <th class="text-primary">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rebuttalDatas as $rebuttalData)
                                    <tr>
                                        <td class="fs-14"><a class="btn btn-sm btn-primary" href="#" target="_blank">Save</a></td>
                                        <td class="fs-16">
                                            <select name ="rebuttal_action" class="form-select form-select-sm"
                                            aria-label="Small select example">
                                            <option value="#">Select any one</option>
                                            <option value="valied">Valied</option>
                                            <option value="in_valied">In-Valied</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="accepted">Accepted</option>
                                            </section>
                                        </td>
                                        <td class="fs-15">{{ $rebuttalData->parameter->parameter }}</td>
                                        <td class="fs-13">{{ $rebuttalData->sub_parameter->sub_parameter }}</td>
                                        <td class="fs-12">{{ $rebuttalData->remark }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
