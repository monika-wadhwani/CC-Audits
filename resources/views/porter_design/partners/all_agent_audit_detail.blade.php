@extends('porter_design.layouts.app')
@section('main')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-success">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="titlTops cardBox my-2 d-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="fw-bold mb-1 boxTittle">All Agent Audits</h3>
                <p class="text-black-50 m-0"></p>
            </div>
            <a href="/agent_feedback">
                <button type="btn" class="btn btn-primary px-4"> 
                    <i class="bi bi-arrow-left pe-2"></i> 
                    Back
                </button>
            </a>
        </div>
        <div class="cardBox my-2 px-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="titleBtm py-2">
                        <h5 class="m-0 fs-14">All Audits</h5>
                    </div>
                    <div class="auditsScroll mainTbl pe-1">
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput1" class="form-label">Agent Email:</label>
                                <input type="text" class="form-control" readonly id="exampleFormControlInput1" placeholder="" value="{{ $raw_data->agent_name }}">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput1" class="form-label">CRN No./Order ID:</label>
                                <input type="text" class="form-control" readonly id="exampleFormControlInput1" placeholder="" value="{{ $raw_data->crn_no_order_id }}">
                            </div>
                        </div>

                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Vehical Type</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->vehicle_type }}" id="exampleFormControlInput4" placeholder="Vehical Type">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput5" class="form-label">Refrence No:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->reference_no }}" id="exampleFormControlInput5" placeholder="Reference No.">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Call ID</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->call_id }}" id="exampleFormControlInput4" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput5" class="form-label">Audit Date:</label>
                                <input type="text" class="form-control" readonly value="{{ $audit_data->audit_date }}" id="exampleFormControlInput5" placeholder="">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput4" class="form-label">Partner</label>
                                <input type="text" readonly class="form-control" value="{{ $audit_data->partner->name }}" id="exampleFormControlInput4" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput5" class="form-label">Client:</label>
                                <input type="text" readonly class="form-control" value="{{ $audit_data->client->name }}" id="exampleFormControlInput5" placeholder="Client">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput6" class="form-label">QA / QTL Name:</label>
                                <input type="text" class="form-control" readonly value="{{ $audit_data->qa_qtl_detail->name }}" id="exampleFormControlInput6" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput7" class="form-label">TL Name:</label>
                                <input type="text" readonly class="form-control" id="exampleFormControlInput7" value="{{ $raw_data->tl_details->name }}" placeholder="">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput8" class="form-label">Call Sub Type:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->call_sub_type }}" id="exampleFormControlInput8" placeholder="-">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput9" class="form-label">Call Type:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->call_type }}" id="exampleFormControlInput9" placeholder="-">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput8" class="form-label">Issues:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->issues }}" id="exampleFormControlInput8" placeholder="-">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput9" class="form-label">Sub Issues:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->sub_issues }}" id="exampleFormControlInput9" placeholder="-">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput8" class="form-label">Customer Name:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->customer_name }}" id="exampleFormControlInput8" placeholder="-">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput9" class="form-label">Disposition:</label>
                                <input type="text" readonly class="form-control" value="{{ $raw_data->disposition }}" id="exampleFormControlInput9" placeholder="-">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput10" class="form-label">QRC 1:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->qrc_1 }}" id="exampleFormControlInput10" placeholder="-">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput11" class="form-label">QRC 2:</label>
                                <input type="text" class="form-control" readonly value="{{ $audit_data->qrc_2 }}" id="exampleFormControlInput11" placeholder="">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput12" class="form-label">Language 1:</label>
                                <input type="text" class="form-control" readonly value="{{ $raw_data->language }}" id="exampleFormControlInput12" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput13" class="form-label">Language 2:</label>
                                <input type="text" class="form-control" readonly value="{{ $audit_data->language_2 }}" id="exampleFormControlInput13" placeholder="">
                            </div>
                        </div>
                        <??>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput12" class="form-label">Scanerio:</label>
                                <input type="text" class="form-control" title="{{ $audit_data->scanerio }}" readonly value="{{ $audit_data->scanerio }}" id="exampleFormControlInput12" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput13" class="form-label">Scanerio Codes:</label>
                                <input type="text" class="form-control" title="{{ $audit_data->scanerio_codes }}" readonly value="{{ $audit_data->scanerio_codes }}" id="exampleFormControlInput13" placeholder="">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput12" class="form-label">Error Reason Type:</label>
                                <input type="text" class="form-control" readonly value="{{ $audit_data->error_reason_type }}" id="exampleFormControlInput12" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput13" class="form-label">Error Reasons:</label>
                                <input type="text" class="form-control" title="{{ $audit_data->error_code_reasons }}" readonly value="{{ $audit_data->error_code_reasons }}" id="exampleFormControlInput13" placeholder="">
                            </div>
                        </div>
                        <div class="form-contentMet form-contentMetGreen">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Error Code:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" title="{{ $audit_data->new_error_code }}" readonly value="{{ $audit_data->new_error_code }}" id="exampleFormControlInput14" placeholder="">
                                </div>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput15" class="form-label">Overall Score:</label>
                                <div class="position-relative">
                                    <input type="number" class="form-control" readonly value="{{ $audit_data->overall_score }}" id="exampleFormControlInput15" placeholder="89.25">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput16" class="form-label">Call timestamp:</label>
                                <input type="number" class="form-control" readonly value="{{ $raw_data->call_time }}" id="exampleFormControlInput16" placeholder="">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput17" class="form-label">Costomer contact number:</label>
                                <input type="number" class="form-control" readonly value="{{ $raw_data->phone_number }}" id="exampleFormControlInput17" placeholder="-">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput18" class="form-label">Call status:</label>
                                <input type="text" readonly class="form-control" value="{{ $audit_data->good_bad_call === 0 ? 'Bed cal' : 'Good call' }}" id="exampleFormControlInput18" placeholder="philippe@mamamoney.co.za">
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput19" class="form-label">Call Duration:</label>
                                <input type="number" readonly  class="form-control" value="{{ round($raw_data->call_duration, 3) }}" id="exampleFormControlInput19" placeholder="442323">
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Is Call Fatal:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" value="{{ $audit_data->is_critical == 1 ? 'Fatal' : 'Non Fatal' }}" readonly id="exampleFormControlInput14" placeholder="NO">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput2" class="form-label">Select Feedback Language</label>
                                <select readonly class="form-select" aria-label="Default select example" name="lang"
                                    onchange="change_language(this.value,'{{ $audit_data->feedback }}');">
                                    <option value="0">Select Language</option>
                                    <option value="en">English</option>
                                    <option value="mr">Marathi</option>
                                    <option value="gu">Gujarati</option>
                                    <option value="ml">Malyalam</option>
                                    <option value="te">Telugu</option>
                                    <option value="ta">Tamil</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Campaign Name:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $raw_data->campaign_name }}" id="exampleFormControlInput14" placeholder="NO">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Audit Type:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $audit_data->audit_type }}" id="exampleFormControlInput14">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Location:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $raw_data->location }}" id="exampleFormControlInput14" placeholder="NO">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Case Id:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $audit_data->case_id }}"  id="exampleFormControlInput14">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">QRC For QA:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $audit_data->qrc_for_qa }}" id="exampleFormControlInput14" placeholder="NO">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput14" class="form-label">Order Stage:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" readonly value="{{ $audit_data->order_stage }}" id="exampleFormControlInput14">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Feedback</label>
                                <textarea class="form-control" name="" id="feedback_lang" cols="50" rows="6" readonly="readonly">{{ $audit_data->feedback }}</textarea>
                            </div>
                        </div>
                        <div class="form-contentMet">
                            <div class="mb-3 w-100">
                                <label for="exampleFormControlInput" class="form-label">Overall Summary</label>
                                <textarea class="form-control" name="" id="feedback_lang" cols="50" rows="7" readonly="readonly">{{ $audit_data->overall_summary }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="auditsWithoutScroll">
                        <h5>Parameter Wise Compliance</h5>
                        <div class="table-responsive w-100 mainTbl tableOfWise ">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-start">Name</th>
                                        <th class="text-end">Fatal Count</th>
                                        <th class="text-end">Fail Count</th>
                                        <th class="text-end">Non- Compliance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($final_data as $key => $val)
                                        <tr>
                                            <td class="text-primary">{{ $val['name'] }}</td>
                                            <td class="text-end">{{ $val['critical_count'] }}</td>
                                            <td class="text-end">{{ $val['fail_count'] }}</td>
                                            <td class="text-end">{{ $val['score'] }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <?php
                        $url = '';
                        if (strlen($audit_data->good_bad_call_file) > 0) {
                            $path_name = str_replace('https://qmtool.s3.ap-south-1.amazonaws.com/', '', $audit_data->good_bad_call_file);
                            $url = Storage::disk('s3')->temporaryUrl(
                                $path_name,
                                now()->addMinutes(8640), //Minutes for which the signature will stay valid
                            );
                        }
                        ?>
                        <div class="listen-call d-flex mb-4 gap-2">
                            <div class="playListen w-100">
                                <p class="text-primary pt-3 pb-2">Listen Call:</p>
                                <div class="wrapperomg">
                                    <audio controls>
                                        <source src="{{ $url }}" type="audio/ogg">
                                        <source src="{{ $url }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <!-- First Audio Player -->
                                    {{-- <div class="music-container" id="music-container1">
                                        <div class="music-info">
                                            <h4 id="title1">Song Title 1</h4>
                                        </div>
                                        <audio src="img/4.mp3" id="audio1" class="audio audio_player"
                                            preload="metadata"></audio>

                                        <div class="navigation">
                                            <button id="backSkip1" class="action-btn"
                                                onclick="skipBackward(player1.audioElement, 15)">
                                                <img src="img/prev1.png" alt="">
                                            </button>
                                            <button id="play1" data-audio-id="audio1"
                                                class="action-btn action-btn-big">
                                                <i class="fa fa-play"></i>
                                            </button>
                                            <button id="forwardSkip1" class="action-btn"
                                                onclick="skipForward(player1.audioElement, 15)">
                                                <img src="img/prev2.png" alt="">
                                            </button>
                                            <div class="progress-container progress-range" id="progress-container1">
                                                <div class="progress progress-bar" id="progress1"></div>
                                            </div>
                                            <button id="muteButton1" class="action-btn speaker"
                                                onclick="toggleMute(player1.audioElement, this)">
                                                <i id="speaker_icon1" class="fa fa-volume-up" aria-hidden="true"></i>
                                            </button>
                                            <div class="time">
                                                <span class="time-elapsed" id="time-elapsed1">00:00</span>
                                                <span class="time-duration" id="time-duration1">3:37</span>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                            </div>
                            <?php
                            $url ="";
                            if(strlen($audit_data->feedback_to_agent_recording) > 0){
                            $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $audit_data->feedback_to_agent_recording);
                            $url = Storage::disk('s3')->temporaryUrl(
                                $path_name,
                                now()->addMinutes(8640) //Minutes for which the signature will stay valid
                                );
                            }
                            ?>
                        <div class="listen-call d-flex mb-4 gap-2">
                            <div class="playListen w-100">
                                <p class="text-primary pt-3 pb-2">Feedback to Agent:</p>
                                <div class="wrapperomg">
                                    <audio controls>
                                        <source src="{{$url}}" type="audio/ogg">
                                        <source src="{{$url}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <!-- Second Audio Player -->
                                    {{-- <div class="music-container" id="music-container2">
                                        <audio src="img/4.mp3" id="audio2" class="audio audio_player"
                                            preload="metadata"></audio>
                                        <div class="navigation">
                                            <button id="backSkip2" class="action-btn"
                                                onclick="skipBackward(player2.audioElement, 15)">
                                                <img src="img/prev1.png" alt="">
                                            </button>
                                            <button id="play2" data-audio-id="audio2"
                                                class="action-btn action-btn-big">
                                                <i class="fa fa-play"></i>
                                            </button>
                                            <button id="forwardSkip2" class="action-btn"
                                                onclick="skipForward(player2.audioElement, 15)">
                                                <img src="img/prev2.png" alt="">
                                            </button>

                                            <div class="progress-container progress-range" id="progress-container2">
                                                <div class="progress progress-bar" id="progress2"></div>
                                            </div>
                                            <button id="muteButton2" class="action-btn speaker"
                                                onclick="toggleMute(player2.audioElement, this)">
                                                <i id="speaker_icon2" class="fa fa-volume-up" aria-hidden="true"></i>
                                            </button>
                                            <div class="time">
                                                <span class="time-elapsed" id="time-elapsed2">00:00</span>
                                                <span class="time-duration" id="time-duration2">3:37</span>
                                            </div>
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                        </div>
                        <div class="fourBoxes">
                            <div class="audioObserve">
                                <div class="audioRais audioRais1 border border-primary rounded mb-3">
                                    <img src="{{ asset('assets/design/img/gallery.png') }}" width="35px"
                                        alt="img">
                                    <p class="text-primary mb-0">Audit Observation Details</p>
                                </div>
                                <div class="audioRais audioRais2 border border-warning rounded mb-3">
                                    <img src="{{ asset('assets/design/img/typing-note.png') }}" width="35px"
                                        alt="img">
                                    <p class="text-warning mb-0">Plan of Action</p>
                                </div>
                            </div>
                            <div class="audioObserve">
                                <div class=" border border-danger rounded mb-0">
                                    <a href="{{ url('partner/single_audit_detail/' . Crypt::encrypt($audit_data->id)) }}"><img
                                            src="{{ asset('assets/design/img/type-msg.png') }}" width="35px"
                                            alt="img">
                                        <p class="text-danger mb-0">Raise Rebuttal</p>
                                    </a>
                                </div>
                                <div class="audioRais audioRais4 border border-success rounded mb-0">
                                    <img src="{{ asset('assets/design/img/msg.png') }}" width="35px" alt="img">
                                    <p class="text-success mb-0">Feedback</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datatables').DataTable();
        });
    </script>
    <script>
        function change_language(lang, text) {


            var arr = {
                data: {
                    question: text
                },
                lang: [lang]
            };

            $.ajax({
                type: "POST",
                url: "https://api.doyoursurvey.com:3009/client-survey/translateForRavi",

                data: JSON.stringify(arr),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,
                success: function(Data) {
                    //console.log(Data);
                    $("#feedback_lang").html(Data.data);

                }
            });
        }
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection
