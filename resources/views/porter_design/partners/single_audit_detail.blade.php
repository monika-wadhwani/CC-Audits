@extends('porter_design.layouts.app')
@section('main')

<div class="container-fluid">
    <div class="titlTops cardBox my-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                <h4 class="fw-bold mb-1 boxTittle">Audits</h4>
                <p class="text-black-50 m-0">Welcome Back!</p>
            </div>
            <div class="">
            <a href="/agent_feedback"> <button type="btn" class="btn btn-primary px-4"> <i class="bi bi-arrow-left pe-2"></i>
                    Back</button></a>        
            </div>
        </div>
    </div>
        @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        </div>
        @endif
    {!! Form::open(
                array(
                    'route' => 'raise_rebuttal_new', 
                    'class' => 'kt-form',
                    'role'=>'form',
                    'data-toggle'=>"validator")
                ) !!}

                @csrf
                <input type="hidden" name="audit_id" value = "{{Crypt::encrypt($audit_data->id)}}">
                <input type="hidden" name="raw_data_id" value = "{{Crypt::encrypt($audit_data->raw_data_id)}}">
        <div class="cardBox d-flex justify-content-between align-items-center mb-2">
            <h6 class="fs-14 fw-bold">Raise Rebuttal</h6>
            <button type="submit" id="save" disabled class="btn btn-primary">Save</button>
        </div>
        {{-- <script>
            function onSubmit(){
            
            }
        </script> --}}
    <div class="cardBox">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-home" aria-labelledby="pills-home-tab" role="tabpanel">
                <div class="d-flex justify-content-between writeplan">
                    <lottie-player src="/assets/design/img/Creatinganewone.json" background="transparent" speed="1"
                        style="width: 260px;" loop="" autoplay=""></lottie-player>
                    <div class="overallContent w-100 ms-3 my-4  ">
                        <span>Write Plan Of Action</span>
                        <div class="form-floating">
                            <textarea class="form-control ps-0" placeholder="Leave a comment here"
                                id="floatingTextarea"></textarea>
                            <label for="floatingTextarea ps-0">Write here</label>
                        </div>
                        <div class="recordingAction">
                            <span>Record plan of Action</span>
                            <div class=""></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade active show" id="pills-profile" aria-labelledby="pills-profile-tab"
                role="tabpanel">
                <div class="row my-2">
                    <div class="col-lg-4">
                        <div class="behaviourWise">
                            <div class="nav flex-lg-column nav-pills" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">

                                @foreach($final_data as $key=>$item)
                                    @if($item['critical_count'] > 0)
                               
                                    <button class="nav-link active one" data-bs-toggle="pill"
                                        data-bs-target="#parameter_tab_{{$key}}" type="button">
                                        <div class="Creationtab">
                                        
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="">
                                                    <span class="text-start">{{$item['name']}}</span>
                                                    <h6 class="fw-normal mb-1">Score : <span>{{$item['score']}}</span></h6>
                                                </div>
                                                <div class="ticketemoji">
                                                    <span class="d-flex flex-column text-center">Critical
                                                        <b>{{$item['critical_count']}}</b></span>
                                                    <lottie-player src="/assets/design/img/3aa13dedcc09bf047024d8724f418f2e.json"
                                                        autoplay loop
                                                        style="height: 65px; width: 65px;"></lottie-player>
                                                </div>
                                            </div>
                                            <hr class="my-2 ">
                                            <div class="totalparameter">
                                                <div
                                                    class="d-flex align-items-center justify-content-between totalparameterinnr">
                                                    <div
                                                        class="d-flex align-items-center justify-content-between w-100 pe-2">
                                                        <span>Sub <br> Parameter</span>
                                                        <p>{{$item['critical_count']+$item['pass_count']+$item['fail_count']}}</p>
                                                    </div>
                                                    <div class="detailPass d-flex gap-2 bg-white p-2 rounded-2">
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/likegreen.svg" class="me-2" width="14"
                                                                alt="">pass : {{$item['pass_count']}}</span>
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/deslikered.svg" class="me-2" width="14"
                                                                alt="">Fail : {{$item['fail_count']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <strong class="text-white">
                                                <img src="/assets/design/img/like.svg" class="me-2" width="16" alt="">Critical 
                                            </strong>
                                           
                                        </div>
                                    </button>
                                    @elseif($item['fail_count'] > 0)   
                                
                                    <button class="nav-link two" data-bs-toggle="pill" data-bs-target="#parameter_tab_{{$key}}"
                                        type="button">
                                        <div class="Creationtab">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="">
                                                    <span class="text-start">{{$item['name']}}</span>
                                                    <h6 class="fw-normal mb-1">Score: <span>{{$item['score']}}</span></h6>
                                                </div>
                                                <div class="ticketemoji">
                                                    <span class="d-flex flex-column text-center">fail
                                                        <b>{{$item['fail_count']}}</b></span>
                                                    <lottie-player src="/assets/design/img/refuse.json" autoplay loop
                                                        style="height: 65px; width: 65px;"></lottie-player>
                                                </div>
                                            </div>
                                            <hr class="my-2 ">
                                            <div class="totalparameter">
                                                <div
                                                    class="d-flex align-items-center justify-content-between totalparameterinnr">
                                                    <div
                                                        class="d-flex align-items-center justify-content-between w-100 pe-2">
                                                        <span>Sub <br> Parameter</span>
                                                        <p>{{$item['critical_count']+$item['pass_count']+$item['fail_count']}}</p>
                                                    </div>
                                                    <div class="detailPass d-flex gap-2 bg-white p-2 rounded-2">
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/likegreen.svg" class="me-2" width="14"
                                                                alt="">pass : {{$item['pass_count']}}</span>
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/deslikered.svg" class="me-2" width="14"
                                                                alt="">Critical : {{$item['critical_count']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <strong class="text-white orangetwo">
                                                <img src="/assets/design/img/like.svg" class="me-2" width="16" alt="">Fail
                                            </strong>

                                        </div>
                                    </button>
                                    @else
                            
                                    <button class="nav-link three" data-bs-toggle="pill" data-bs-target="#parameter_tab_{{$key}}"
                                        type="button">
                                        <div class="Creationtab">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="">
                                                    <span class="text-start">{{$item['name']}}</span>
                                                    <h6 class="fw-normal mb-1">Score : <span>{{$item['score']}}</span></h6>
                                                </div>
                                                <div class="ticketemoji">
                                                    <span class="d-flex flex-column text-center">Passed
                                                        <b>{{$item['pass_count']}}</b></span>
                                                    <lottie-player src="/assets/design/img/happy-face.json" autoplay loop
                                                        style="height: 65px; width: 65px;"></lottie-player>
                                                </div>
                                            </div>
                                            <hr class="my-2 ">
                                            <div class="totalparameter">
                                                <div
                                                    class="d-flex align-items-center justify-content-between totalparameterinnr">
                                                    <div
                                                        class="d-flex align-items-center justify-content-between w-100 pe-2">
                                                        <span>Sub <br> Parameter</span>
                                                        <p>{{$item['critical_count']+$item['pass_count']+$item['fail_count']}}</p>
                                                    </div>
                                                    <div class="detailPass d-flex gap-2 bg-white p-2 rounded-2">
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/likegreen.svg" class="me-2" width="14"
                                                                alt="">pass : {{$item['pass_count']}}</span>
                                                        <span class="d-flex align-items-center m-0"> <img
                                                                src="/assets/design/img/deslikered.svg" class="me-2" width="14"
                                                                alt="">Fail : {{$item['fail_count']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <strong class="text-white orangethree">
                                                <img src="/assets/design/img/like.svg" class="me-2" width="16" alt="">Passed
                                            </strong>

                                        </div>
                                    </button>
                                    @endif
                                @endforeach
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 ">
                        <div class="tab-content">
                        @php
                        $parameter_active = 1;
                        @endphp
                        @foreach($final_data as $key=>$item)
                            <div class="tab-pane fade show @if($parameter_active == 1) active @endif" id="parameter_tab_{{$key}}">
                                <div class="criticalParameters">
                                    <ul class="nav nav-pills w-100" id="pills-tab">
                                        <li class="nav-item one border-bottom-0 border-end-0">
                                            <button class="nav-link active" data-bs-toggle="pill"
                                                data-bs-target="#critical-tab_{{$key}}" type="button">
                                                <div class="criticalfirstbx">
                                                    <h6>Critical Sub Parameters</h6>
                                                    <span class="subparametercont">{{$item['critical_count']}}</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item border-bottom-0">
                                            <button class="nav-link failedtb" data-bs-toggle="pill"
                                                data-bs-target="#Failed-tab_{{$key}}" type="button">
                                                <div class="criticalfirstbx">
                                                    <h6>Failed Sub Parameters</h6>
                                                    <span class="subparametercont two">{{$item['fail_count']}}</span>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="nav-item  border-bottom-0 border-start-0">
                                            <button class="nav-link passedtb" data-bs-toggle="pill"
                                                data-bs-target="#passed-tab_{{$key}}" type="button">
                                                <div class="criticalfirstbx">
                                                    <h6>Passed Sub Parameters</h6>
                                                    <span class="subparametercont three">{{$item['pass_count']}}</span>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="critical-tab_{{$key}}">

                                            <div
                                                class="table-responsive w-100 mainTbl criticalparatbl border-end border-start">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr class="position-sticky top-0 z-1">
                                                            <th>Sr. No</th>
                                                            <th>Sub-Parameter</th>
                                                            <th>Obeservation</th>
                                                            <th>Scored</th>
                                                            <th>Observation Approval</th>
                                                            <th>Remarks</th>
                                                            <th>Artifact</th>
                                                            <th class="text-end">Screenshot</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $count = 1;
                                                    @endphp
                                                        @foreach($item['sp'] as $skey=>$svalue)
                                                      
                                                            @if(isset($svalue['is_critical']) && $svalue['is_critical'] ==1)
                                                                <tr>
                                                                    <td>{{$count}}</td>
                                                                    <td class="tableDescription">{{$svalue['name']}}</td>
                                                                    <td>
                                                                        @if($item['qm_sheet_id'] == 137)
                                                                            @if($svalue['selected_option']=='Pass')
                                                                                <span class="badgeGreen badged">Yes</span>
                                                                            @else

                                                                                <span class="badgeRed badged">No</span>
                                                                            @endif
                                                                        @else
                                                                        <span class="badgeRed badged">{{$svalue['selected_option']}}</span>
                                                                        
                                                                        @endif
                                                                    <td>{{$svalue['scored']}}</td>
                                                                    <td>
                                                                        <select name ="desired_option[]" class="form-select form-select-sm"
                                                                            aria-label="Small select example">
                                                                            <option value="" selected>select</option>
                                                                            @if($item['qm_sheet_id'] == 137)
                                                                                @if($svalue['selected_option']!='Pass')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_1_yes">Yes</option>
                                                                                @endif
                                                                                @if($svalue['selected_option']!='Fail')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_2_no">No</option>
                                                                                @endif
                                                                                @if($svalue['selected_option']!='Critical')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_4_na">N/A</option>
                                                                                @endif
                                                                            @else 
                                                                                @if($svalue['para_detail']['pass']==1 && $svalue['selected_option']!='Pass')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_1_pass">Yes</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['fail']==1 && $svalue['selected_option']!='Fail')
                                                                                <option value="{{$key}}_2_fail">No</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['critical']==1 && $svalue['selected_option']!='Critical')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_3_critical">Critical</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['na']==1 && $svalue['selected_option']!='N/A')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_4_N/A">N/A</option>
                                                                                @endif 
                                                                            @endif
                                                                        </select></td>
                                                                    <td class="remarktextarea">
                                                                        <div class="form-floating">
                                                                            <textarea class="form-control remarks" oninput="validateTextarea()" title="Please enter at least 30 characters" name="remarks[]"></textarea>
                                                                            <p id="lengthError" style="color: red;"></p>
                                                                        </div>
                                                                    </td>
                                                                    <td class="remarktextarea">
                                                                        <div class="form-floating">
                                                                            <img name="qc_to_qa_artifact[]" src="{{$value['qc_to_qa_artifact'] ?? ''}}" alt="">
                                                                            <label for="floatingTextarea">Artifact</label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="uploader">
                                                                            <label class="uploadbtn" for="upload"><img
                                                                                    src="/assets/design/img/upload.svg" class="me-2"
                                                                                    width="15" height="15" alt="">
                                                                                Upload File</label>
                                                                            <input  type="file" name="artefects[]"
                                                                                accept="image/*"
                                                                                onchange="handleFileUpload(this)" />
                                                                            <!-- after file upload  -->
                                                                            <div class="uploadbtn imagepath"
                                                                                style="display: none;"><img
                                                                                    src="/assets/design/img/upload.svg" class="me-1"
                                                                                    width="13" height="13" alt=""><span
                                                                                    class="me-auto" id="file-name">
                                                                                    imageqwefgthyjuk1.jpg </span><button
                                                                                    class="border-0"
                                                                                    onclick="clearFileUpload()"><img
                                                                                        src="/assets/design/img/Iconmaterial-close.svg"
                                                                                        width="10" alt=""
                                                                                        class="ms-2"></button></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @php
                                                            $count++;
                                                            @endphp
                                                        @endforeach

                                                        
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="tab-pane fade" id="Failed-tab_{{$key}}">

                                            <div
                                                class="table-responsive w-100 mainTbl criticalparatbl border-end border-start">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr class="position-sticky top-0 z-1">
                                                            <th>Sr. No</th>
                                                            <th>Sub-Parameter</th>
                                                            <th>Obeservation</th>
                                                            <th>Scored</th>
                                                            <th>Observation Approval</th>
                                                            <th>Remarks</th>
                                                            <th class="text-end">Screenshot</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $count = 1;
                                                    @endphp 
                                                    @foreach($item['sp'] as $skey=>$svalue)
                                                            @if(isset($svalue['is_critical']) && $svalue['is_critical'] == 0 && $svalue['scored'] == 0)
                                                                <tr>
                                                                    <td>{{$count}}</td>
                                                                    <td class="tableDescription">{{$svalue['name']}}</td>
                                                                    <td>
                                                                    @if($item['qm_sheet_id'] == 137) 
                                                                        @if($svalue['selected_option']=='Pass')
                                                                            <span class="badgeGreen badged">Yes</span>
                                                                        @else

                                                                            <span class="badgeRed badged">No</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="badgeRed badged">{{$svalue['selected_option']}}</span>
                                                                    
                                                                    @endif
                                                                    <td>{{$svalue['scored']}}</td>
                                                                    <td><select name ="desired_option[]" class="form-select form-select-sm"
                                                                            aria-label="Small select example">
                                                                            <option value="" selected>select</option>
                                                                            @if($item['qm_sheet_id'] == 137)
                                                                            @if($svalue['selected_option']!='Pass')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_1_yes">Yes</option>
                                                                            @endif
                                                                            @if($svalue['selected_option']!='Fail')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_2_no">No</option>
                                                                            @endif
                                                                            @if($svalue['selected_option']!='Critical')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_4_na">N/A</option>
                                                                            @endif
                                                                            @else 
                                                                            @if($svalue['para_detail']['pass']==1 && $svalue['selected_option']!='Pass')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_1_pass">Yes</option>
                                                                            @endif 
                                                                            @if($svalue['para_detail']['fail']==1 && $svalue['selected_option']!='Fail')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_2_fail">No</option>
                                                                            @endif 
                                                                            @if($svalue['para_detail']['critical']==1 && $svalue['selected_option']!='Critical')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_3_critical">Critical</option>
                                                                            @endif 
                                                                            @if($svalue['para_detail']['na']==1 && $svalue['selected_option']!='N/A')
                                                                            <option value="{{$key}}_{{$svalue['id']}}_4_N/A">N/A</option>
                                                                            @endif 
                                                                        @endif
                                                                        </select></td>
                                                                    <td class="remarktextarea">
                                                                        <div class="form-floating">
                                                                                <textarea class="form-control remarks" oninput="validateTextarea()" title="Please enter at least 30 characters" name="remarks[]"></textarea>
                                                                                <p id="lengthError" style="color: red;"></p>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="uploader">
                                                                            <label class="uploadbtn" for="upload"><img
                                                                                    src="/assets/design/img/upload.svg" class="me-2"
                                                                                    width="15" height="15" alt="">
                                                                                Upload File</label>
                                                                            <input  type="file" name="artefects[]"
                                                                                accept="image/*"
                                                                                onchange="handleFileUpload(this)" />
                                                                            <!-- after file upload  -->
                                                                            <div class="uploadbtn imagepath"
                                                                                style="display: none;"><img
                                                                                    src="/assets/design/img/upload.svg" class="me-1"
                                                                                    width="13" height="13" alt=""><span
                                                                                    class="me-auto" id="file-name">
                                                                                    imageqwefgthyjuk1.jpg </span><button
                                                                                    class="border-0"
                                                                                    onclick="clearFileUpload()"><img
                                                                                        src="/assets/design/img/Iconmaterial-close.svg"
                                                                                        width="10" alt=""
                                                                                        class="ms-2"></button></div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @php
                                                            $count++;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>
                                        <div class="tab-pane fade" id="passed-tab_{{$key}}">

                                            <div
                                                class="table-responsive w-100 mainTbl criticalparatbl border-end border-start">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr class="position-sticky top-0 z-1">
                                                            <th>Sr. No</th>
                                                            <th>Sub-Parameter</th>
                                                            <th>Obeservation</th>
                                                            <th>Scored</th>
                                                            <th>Observation Approval</th>
                                                            <th>Remarks</th>
                                                            <th class="text-end">Screenshot</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                    $count = 1;
                                                    @endphp  
                                                    @foreach($item['sp'] as $skey=>$svalue)
                                                        @if(isset($svalue['is_critical']) && $svalue['is_critical']  == 0 && $svalue['scored'] > 0)
                                                            <tr>
                                                                <td>{{$count}}</td>
                                                                <td class="tableDescription">{{$svalue['name']}}</td>
                                                                <td >
                                                                @if($item['qm_sheet_id'] == 137)
                                                                    @if($svalue['selected_option']=='Pass')
                                                                        <span class="badgeGreen badged">Yes</span>
                                                                    @else

                                                                        <span class="badgeRed badged">No</span>
                                                                    @endif
                                                                @else
                                                                    <span class="badgeRed badged">{{$svalue['selected_option']}}</span>
                                                                @endif
                                                                <td>{{$svalue['scored']}}</td>
                                                                <td><select name ="desired_option[]" class="form-select form-select-sm"
                                                                        aria-label="Small select example">
                                                                        <option value="" selected>select</option>
                                                                            @if($item['qm_sheet_id'] == 137)
                                                                                @if($svalue['selected_option']!='Pass')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_1_yes">Yes</option>
                                                                                @endif
                                                                                @if($svalue['selected_option']!='Fail')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_2_no">No</option>
                                                                                @endif
                                                                                @if($svalue['selected_option']!='Critical')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_4_na">N/A</option>
                                                                                @endif
                                                                            @else 
                                                                                @if($svalue['para_detail']['pass']==1 && $svalue['selected_option']!='Pass')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_1_pass">Pass</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['fail']==1 && $svalue['selected_option']!='Fail')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_2_fail">Fail</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['critical']==1 && $svalue['selected_option']!='Critical')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_3_critical">Critical</option>
                                                                                @endif 
                                                                                @if($svalue['para_detail']['na']==1 && $svalue['selected_option']!='N/A')
                                                                                <option value="{{$key}}_{{$svalue['id']}}_4_N/A">N/A</option>
                                                                                @endif 
                                                                            @endif
                                                                    </select></td>
                                                                <td class="remarktextarea">
                                                                    <div class="form-floating">
                                                                            <textarea class="form-control remarks" oninput="validateTextarea()" title="Please enter at least 30 characters" name="remarks[]"></textarea>
                                                                            <p id="lengthError" style="color: red;"></p>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="uploader">
                                                                        <label class="uploadbtn" for="upload"><img
                                                                                src="/assets/design/img/upload.svg" class="me-2"
                                                                                width="15" height="15" alt="">
                                                                            Upload File</label>
                                                                        <input  type="file" name="artefects[]"
                                                                            accept="image/*"
                                                                            onchange="handleFileUpload(this)" />
                                                                        <!-- after file upload  -->
                                                                        <div class="uploadbtn imagepath"
                                                                            style="display: none;"><img
                                                                                src="/assets/design/img/upload.svg" class="me-1"
                                                                                width="13" height="13" alt=""><span
                                                                                class="me-auto" id="file-name">
                                                                                imageqwefgthyjuk1.jpg </span><button
                                                                                class="border-0"
                                                                                onclick="clearFileUpload()"><img
                                                                                    src="/assets/design/img/Iconmaterial-close.svg"
                                                                                    width="10" alt=""
                                                                                    class="ms-2"></button></div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @php
                                                        $count++;
                                                        @endphp
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                            $parameter_active = 0;
                            @endphp
                        @endforeach   
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
<!-- modal start feedback -->
<div class="modal fade" id="feedback" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header boxShadow m-2">
                <div>
                    <h1 class="modal-title fs-6">Feedback</h1>
                    <span class="subtittle">Lorem Ipsum is a dummy text</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <lottie-player src="/assets/design/img/feedback-gauge.json" class="mx-auto" background="transparent" speed="1"
                    style="width: 220px; height: 220px;" loop autoplay></lottie-player>
                <div class="position-relative mb-2">
                    <span class="selectHeading">Select Status</span>
                    <select class="form-select form-select-sm" aria-label="Small select example">

                        <option selected>Accepted</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div class="position-relative mb-2">
                    <span class="selectHeading mb-2">Select Status</span> <br>
                    <input type="text" placeholder="24-5-2023" class="w-100 ">
                </div>
                <div class="position-relative mb-2">
                    <span class="selectHeading mb-2">Select Status</span> <br>
                    <textarea name="" id="feedbackmdl" class="w-100" placeholder="Lorem ipsum "
                        rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button class="btn btn-primary">submit feedback</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    function validateTextarea() {
        var lengthError = document.getElementById('lengthError');
        var textarea = document.getElementById('remarks');
        var saveButton = document.getElementById('save');
        if (textarea.value.length < 30) {
        lengthError.textContent = 'Please enter at least 30 characters.';
        } else {
        lengthError.textContent = '';
        }
        if (textarea.value.length < 30) {
            saveButton.setAttribute('disabled', 'true');
        } else {
            saveButton.removeAttribute('disabled');
        }
    }
</script>
    <script>
        $(document).ready(function () {
            $('.datatables').DataTable();
        });
    </script>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection
