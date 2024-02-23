@extends('porter_design.layouts.app')

    @section('main')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7 h-100">
                    <div class="cardBox cardBoxSpace mb-2 d-flex justify-content-between align-items-center h-100">
                        <div>
                            <h1>Welcome back {{Auth::user()->name}}!</h1>
                            <p>Since your last login on the system, there were:</p>
                            <div class="feedback position-relative d-flex flex-column">
                                <span id = "new_feedback_count"></span>
                                <span id = "audit_count"></span>
                                <span id = "total_rebuttals"></span>
                                <span id = "rebuttal_accepted"></span>
                                <span id = "rebuttal_rejected"></span>
                                <span id = "rebuttal_wip"></span>

                            </div>
                        </div>
                        <div class="pe-3">
                            <lottie-player src="assets/design/img/truck.json" background="transparent" speed="1" style="width: 190px;"
                                loop autoplay></lottie-player>
                        </div>
                    </div>
                    <div class="cardBox cardBoxSpace mb-2">
                        <div class="searchFilter">
                            <h2 class="tittle">Search / Filter</h2>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1">
                                        <label class="form-check-label fs-14" for="flexRadioDefault1">
                                            Select Date Range
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2">
                                        <label class="form-check-label fs-14" for="flexRadioDefault2">
                                            Select Audit Cycle
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <div class="position-relative">

                                        <input type="text" id="checkInDate" class="form-control " value=""
                                            placeholder="20-3-2012 to 12-2-3023">
                                        <img src="assets/design/img/Icon awesome-calendar-alt.svg" class="calenderIcon"
                                            alt="calebdericon">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-5">
                                    <div class="auditCycle">
                                        <span>Select Audit Cycle</span>
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modallobpara">
                                            <img src="assets/design/img/downarrow.svg" alt="arrowdown" width="26px">
                                        </a>

                                    </div>
                                </div>
                                <div class="col-md-3 mt-md-2 mt-lg-0">
                                    <div class="text-end text-sm-center">
                                    <a ><button onclick="get_agent_dashboard()" type="button" class="btn btn-primary">Apply</button></a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="cardBox cardBoxSpace h-100 bg-white">
                                <h2 class="tittle">LOB Wise Score</h2>
                                <select class="form-select form-select-sm selectbg" id="process_list" aria-label="Small select example">
                                    <option selected>Select Parameters</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <div class="VoiceEvaluation mt-3">
                                    <span class="fw-semibold d-block mb-3" id="process_name">Process</span>
                                    <table width="100%">
                                        <tr class="bg-white">
                                            <th>Head</th>
                                            <th>Value</th>
                                        </tr>
                                        <tr>
                                            <td>Audit Count</td>
                                            <td id="process_audit_count"></td>
                                        </tr>
                                        <tr>
                                            <td>FATAL Score</td>
                                            <td id = "with_fatal_score"></td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-0">
                            <div class="callType cardBox cardBoxSpace h-100">
                                <h2 class="tittle">Call Type : As Per System</h2>
                                <ul class="nav nav-pills mt-4 mb-3" id="callTypes-tabs">
                                    <li class="nav-item mb-1">
                                        <button class="nav-link active px-4"  data-bs-toggle="pill"
                                            data-bs-target="#Query-home" type="button">Query</button>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#resolved-tab" type="button">Request</button>
                                    </li>
                                    <li class="nav-item mb-1">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#complete" type="button">Complaint</button>
                                    </li>

                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="Query-home">
                                       
                                        <div class="VoiceEvaluation bg-transparent mt-3 p-0">
                                            <table width="100%">
                                                <tr class="bg-gray">
                                                    <th>Head</th>
                                                    <th>Value</th>
                                                </tr>
                                                <tr>
                                                    <td>Audit Count</td>
                                                    <td id = "query_audits">20</td>
                                                </tr>
                                                <tr>
                                                    <td>Main Score (With Fatal)</td>
                                                    <td id = "query_score">87%</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="resolved-tab" aria-labelledby="pills-profile-tab">
                                        <div class="VoiceEvaluation bg-transparent mt-3 p-0">
                                            <table width="100%">
                                                <tr class="bg-gray">
                                                    <th>Head</th>
                                                    <th>Value</th>
                                                </tr>
                                                <tr>
                                                    <td>Audit Count</td>
                                                    <td id = "request_audits">20</td>
                                                </tr>
                                                <tr>
                                                    <td>Main Score (With Fatal)</td>
                                                    <td id = "request_score">87%</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="complete" aria-labelledby="pills-contact-tab">
                                        <div class="VoiceEvaluation bg-transparent mt-3 p-0">
                                            <table width="100%">
                                                <tr class="bg-gray">
                                                    <th>Head</th>
                                                    <th>Value</th>
                                                </tr>
                                                <tr>
                                                    <td>Audit Count</td>
                                                    <td id = "complaint_audits">20</td>
                                                </tr>
                                                <tr>
                                                    <td>Main Score (With Fatal)</td>
                                                    <td id = "complaint_score">87%</td>
                                                </tr>
                                            </table>
                                        </div>
                                        </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="graphlainechart cardBox cardBoxSpace">
                        <h2 class="tittle">Parameter Wise Compliance</h2>
                        <div id="Parameterschart"></div>
                    </div>
                </div>
                <div class="col-md-5 ps-0">
                    <div class="cardBox h-100">
                        <div class="feedbackhead mainTbl pt-0">
                            <div class="d-flex justify-content-between align-items-center feedbackinner position-sticky top-0 bg-white p-2">
                                <div>
                                    <h2 class="tittle m-0">Feedback Notification</h2>
                                    {{-- <p class="p-0">Simply dummy text of the printing and.</p> --}}
                                </div>
                                <div>
                                    <a href="/agent_feedback"><button type="button" class="btn btn-primary">View All</button></a>
                                </div>
                            </div>

                            @foreach ($all_feedbacks as $item)


                                <div class="clockFeedback mb-4">
                                    <div class="d-flex align-items-center justify-content-center headerYellow">
                                        <lottie-player src="assets/design/img/Clock.json" background="transparent" speed="1"
                                            style="width: 20px; height: 20px;" loop autoplay></lottie-player>
                                        <span>{{time_difference($item->rebuttal_tat,date('Y-m-d H:i:s'))}} Hr Laft</span>
                                    </div>
                                    <div class="fromFeedback p-3 ">
                                        <div class="d-flex justify-content-between flex-wrap mb-2">
                                            <span>Feedback From <p class="text-black">{{$item->auditor->name}}</p></span>
                                            <span>Call Id <p class="text-black">{{$item->raw_data->call_id}}</p></span>
                                            <span>Process Name <p class="text-black">{{$item->process->name}}</p></span>
                                        </div>
                                        <span class="d-block mb-1">Score With Fatal</span>
                                        <div class="progress rounded-1" role="progressbar" aria-label="Success example"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-primary text-end pe-1" style="width: {{$item->with_fatal_score_per}}%">{{$item->with_fatal_score_per}}%</div>
                                        </div>
                                    </div>
                                    @if(Auth::user()->hasRole('agent-tl'))
                                    <div class="text-center pb-4">
                                        <button type="button" class="btn btn-primary">Valid</button>
                                    </div>
                                    @else
                                    <div class="text-center pb-4">
                                        <button type="button" class="btn btn-primary">Feedback Accepted</button>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                            

                        </div>

                    </div>
                </div>
            </div>
            <div class="cardBox cardBoxSpace paretoAnalysiswise mt-2">
                <h2 class="tittle">Pareto Analysis</h2>
                <div id="paretoAnalysis"></div>
            </div>
        </div>
        <!-- modal start  -->
        <div class="modal modalFilter" id="modallobpara">
            <div class="modal-dialog auditsmodl">
                <div class="modal-content">
                    <div class="modal-body p-2">
                        <div class="headingModl d-flex justify-content-between align-baseline">
                            <div class="">
                                <h5 class="fw-semibold">LOB Parameters Filter</h5>
                                <p class="m-0 fs-13">Simply dummy text of the printing and.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="w-100 FilterSec px-3 pt-3">
                            <label class="custLb w-100 mb-4"><input type="radio">Voice Evaluation
                            </label>
                            <label class="custLb w-100 mb-4"><input type="radio">Ticket Evaluation
                            </label>
                            <label class="custLb w-100 mb-4"><input type="radio">WhatsApp Evaluation
                            </label>
                            <label class="custLb w-100 mb-4"><input type="radio">Activation Evaluation
                            </label>
                            
                        </div>
                        <div class="btnSec d-flex w-100 justify-content-center py-4 align-items-center">
                            <a href="#" class="btn btn-primary mx-3" data-bs-dismiss="modal">Apply Filter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

@section('js')
    @include('porter_design.shared.agent_dashbaord_js')

<script>


    agent_overall_score();
    agent_processes();
    get_agent_dashboard(45,'2023-09-15','2023-10-15');
    function agent_overall_score() {
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/agent_overall_score",
            success: function(Data) {
                $("#new_feedback_count").html(Data.new_feedback+" New Feedback Received");
                $("#audit_count").html(Data.audit_count+" New Audits");
                $("#total_rebuttals").html(Data.total_rebuttals+" Rebuttal Raised");
                $("#rebuttal_accepted").html(Data.rebuttal_accepted+" Rebuttal Accepted");
                $("#rebuttal_rejected").html(Data.rebuttal_rejected+" Rebuttal Rejected");
                $("#rebuttal_wip").html(Data.rebuttal_wip+" Rebuttal WIP");
            }
        });
    }

    function agent_processes() {
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/agent_processes",
            success: function(Data) {
                $("#process_list").html(Data);
                
            }
        });
    }

    function get_agent_dashboard(process_id, start_date, end_date){
        var date_range = $('#checkInDate').val();
        console.log(date_range);
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/agent_dashboard_lob/"+process_id+"/"+start_date+"/"+end_date,
            success: function(Data) {
                $("#process_name").html(Data.final_data.process);

                $("#process_audit_count").html(Data.final_data.audit_count);
                $("#with_fatal_score").html(Data.final_data.with_fatal_score.toFixed(2)+"%");
                
                
                $("#query_audits").html(Data.final_data.qrc[0].audit_count);
                $("#query_score").html(Data.final_data.qrc[0].with_fatal_score_per.toFixed(2)+"%");
                $("#request_audits").html(Data.final_data.qrc[1].audit_count);
                $("#request_score").html(Data.final_data.qrc[1].with_fatal_score_per.toFixed(2)+"%");
                $("#complaint_audits").html(Data.final_data.qrc[2].audit_count);
                $("#complaint_score").html(Data.final_data.qrc[2].with_fatal_score_per.toFixed(2)+"%");
            }
        });
    }
</script>
@endsection