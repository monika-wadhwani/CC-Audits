@extends('layouts.app_third')



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>

<script src="https://code.highcharts.com/highcharts-more.js"></script>

<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<style>
    #cover-spin {

        position: fixed;

        width: 100%;

        left: 0;
        right: 0;
        top: 0;
        bottom: 0;

        background-color: rgba(255, 255, 255, 0.7);

        z-index: 9999;

        display: none;

    }

    .kt-portlet__head {

        background-color: #103264;

    }

    .action {

        background-color: #f0501b;

    }

    td,
    th {
        font-size: 12px;
    }

    @-webkit-keyframes spin {

        from {
            -webkit-transform: rotate(0deg);
        }

        to {
            -webkit-transform: rotate(360deg);
        }

    }



    @keyframes spin {

        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }

    }



    #cover-spin::after {

        content: '';

        display: block;

        position: absolute;

        left: 48%;
        top: 40%;

        width: 40px;
        height: 40px;

        border-style: solid;

        border-color: black;

        border-top-color: transparent;

        border-width: 4px;

        border-radius: 50%;

        -webkit-animation: spin .8s linear infinite;

        animation: spin .8s linear infinite;

    }
</style>

@section('sh-title')
    Dashboard
@endsection



@section('sh-detail')
    Partner - Location - Process Wise
@endsection

@section('sh-toolbar')
    <style>
        .plus,
        .minus {
            position: relative;
            display: block;
        }

        .icon-change.plus:after,

        .icon-change.minus:after {
            color: rgb(65, 105, 225);
            position: absolute;
            margin-left: 7px;
            top: 1px;
            font-weight: bold;
        }

        .plus:after {
            content: '+';
        }

        .minus:after {
            content: '\2212';
            top: 1px !important;
        }
    </style>

    <div class="kt-subheader__toolbar">

        <div class="kt-subheader__wrapper">



            <a href="/test_html_new_get" class="btn btn-label-brand btn-bold">

                Detail Dashboard

            </a>

        </div>

    </div>
@endsection

@section('main')

    <div id="cover-spin"></div>
    @if (Auth::user()->hasRole('agent-tl') and sizeof($action_list) > 0)
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head action">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" style="color:white">
                        Action Required
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">

                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
                    <thead>
                        <tr>


                            <th>
                                Call Id
                            </th>
                            <th>
                                Agent
                            </th>
                            <th>
                                Status
                            </th>

                            <th>
                                Score
                            </th>
                            <th>
                                Rebuttal
                            </th>
                            <th>
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($action_list as $row)
                            @if (audit_rebuttal_status($row->audit->rebuttal_status) == 'Raised' and
                                    $row->audit->audit_rebuttal_not_validate_action->count() > 0)
                                <tr>

                                    <td>
                                        {{ $row->call_id }}
                                    </td>
                                    <td>
                                        {{ $row->agent_name }}
                                    </td>

                                    <td>
                                        @if ($row->audit->is_critical)
                                            <span class="text-danger">Fatal</span>
                                        @else
                                            <span class="text-success">Non-Fatal</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ round($row->audit->overall_score) }}%
                                    </td>
                                    <td>
                                        {{ audit_rebuttal_status($row->audit->rebuttal_status) }} ||
                                        {{ $row->audit->audit_rebuttal->count() }}
                                    </td>
                                    <td>
                                        <a href="{{ url('valid_invalid/' . Crypt::encrypt($row->audit->id) . '/1') }}"><span
                                                class="kt-badge kt-badge--success kt-badge--inline">Valid All</span></a>
                                        <a href="{{ url('valid_invalid/' . Crypt::encrypt($row->audit->id) . '/2') }}"><span
                                                class="kt-badge kt-badge--warning kt-badge--inline">Invalid All</span></a>
                                        <a href="{{ url('partner/single_audit_detail/' . Crypt::encrypt($row->audit->id)) }}"><span
                                                class="kt-badge kt-badge--primary kt-badge--inline">View Audit</span></a>

                                    </td>

                                </tr>
                            @endif
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if (Auth::user()->hasRole('agent') and sizeof($action_list) > 0)
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head action">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title" style="color:white">
                        Action Required
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">

                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
                    <thead>
                        <tr>


                            <th>
                                Call Id
                            </th>
                            <th>
                                Agent
                            </th>
                            <th>
                                Status
                            </th>

                            <th>
                                Score
                            </th>
                            <th>
                                Rebuttal
                            </th>
                            <th>
                                Action
                            </th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($action_list as $row)
                            
                                <tr>

                                    <td>
                                        {{ $row->call_id }} 
                                    </td>
                                    <td>
                                        {{ $row->agent_name }}
                                    </td>

                                    <td>
                                        @if ($row->audit->is_critical)
                                            <span class="text-danger">Fatal</span>
                                        @else
                                            <span class="text-success">Non-Fatal</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ round($row->audit->overall_score) }}%
                                    </td>
                                    <td>
                                        {{ audit_rebuttal_status($row->audit->rebuttal_status) }} ||
                                        {{ $row->audit->audit_rebuttal->count() }}
                                    </td>
                                    <td>
                                        <a href="{{url('feedback_accept/'.Crypt::encrypt($row->audit->id)."/1")}}"><span class="kt-badge kt-badge--success kt-badge--inline">Accept</span></a>
								        <a href="{{url('feedback_accept/'.Crypt::encrypt($row->audit->id)."/2")}}"><span class="kt-badge kt-badge--warning kt-badge--inline">Reject</span></a>
                                        <a href="{{url('partner/single_audit_detail/'.Crypt::encrypt($row->audit->id))}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail View">
                                            <i class="la la-eye"></i>
                                        </a>

                                    </td>

                                </tr>
                             
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title" style="color:white">
                    Search / Filter
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            {{ Form::open(['url' => 'agent_dashboard']) }}
            @csrf
            <div class="row">
                <div class="form-group col-lg-4">
                    <div class="pl-5">Select Date Range</div>
                    <div class="d-flex align-items-center">
                        <input type="radio" class="form-check-input position-relative mx-auto mt-0" id="materialUnchecked"
                            name="materialExampleRadios" value="date_range">

                        <input type='text' class="form-control ml-4" name="target_month" id="datepicker123"
                            required="required" />

                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="pl-5">Select Audit Cycle</div>
                    <div class="d-flex align-items-center">
                        <input type="radio" class="form-check-input position-relative mx-auto mt-0" id="materialUnchecked"
                            name="materialExampleRadios" value="audit_cycle">
                        <select class="form-control ml-4" name="month" id="audit_cycle" required="required">

                            <span class="checkmark"></span>
                            <option value="0">Select Audit Cycle</option>
                            @foreach ($final_data['audit_cycle'] as $value)
                                <option value="{{ $value->start_date }} {{ $value->end_date }}">{{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-lg-2">
                    <div class="pl-5">Select Audit Type</div>
                    <div class="d-flex align-items-center">
                        <select class="form-control ml-4" name="audit_type" required="required">
                            <option value="0">External</option>
                            <option value="1">Internal</option>
                            <option value="%">All</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">

                    <br>
                    <input type="submit" onclick="return check_cycle()" style="width: 100px;"
                        class="btn btn-outline-brand d-block" value="Search">

                </div>

            </div>
            {{ Form::close() }}
        </div>
    </div>







    <!--begin:: Widgets/Stats-->

    <div class="kt-portlet">

        <div class="kt-portlet__body  kt-portlet__body--fit">

            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-12 col-xl-12">



                    <!--begin::Total Profit-->

                    <div class="kt-widget24">

                        <div class="kt-widget24__details">

                            <div class="kt-widget24__info">

                                <h4 class="kt-widget24__title">

                                    All LOB Coverage

                                </h4>

                                <span class="kt-widget24__desc">

                                    Target

                                </span>





                            </div>

                            <span class="kt-widget24__stats kt-font-brand">

                                {{ $final_data['coverage']['target'] }}



                            </span>

                        </div>

                        <div class="progress progress--sm">

                            <div class="progress-bar kt-bg-brand" role="progressbar"
                                style="width: {{ $final_data['coverage']['achived_per'] }}%;" aria-valuenow="50"
                                aria-valuemin="0" aria-valuemax="100"></div>

                        </div>

                        <div class="kt-widget24__action">

                            <span class="kt-widget24__change">

                                Achieved

                            </span>

                            <span class="kt-widget24__number">

                                {{ $final_data['coverage']['achived'] }} ({{ $final_data['coverage']['achived_per'] }} %)

                            </span>

                        </div>

                    </div>

                    <!--end::Total Profit-->

                </div>

                <div class="col-md-12 col-lg-6 col-xl-6" hidden>



                    <div class="kt-widget24">

                        <div class="kt-widget24__details">

                            <div class="kt-widget24__info">

                                <h4 class="kt-widget24__title">

                                    Rebuttal

                                </h4>

                                <span class="kt-widget24__desc">

                                    Raised

                                </span>

                            </div>

                            <span class="kt-widget24__stats kt-font-warning">

                                {{ $final_data['rebuttal']['raised'] }} - {{ $final_data['rebuttal']['rebuttal_per'] }}%

                            </span>

                        </div>

                        <div class="progress progress--sm">

                            <div class="progress-bar kt-bg-warning" role="progressbar"
                                :style="'width: ' + rebuttal.accepted_per + '%'" aria-valuenow="50" aria-valuemin="0"
                                aria-valuemax="100"></div>

                        </div>

                        <div class="kt-widget24__action">

                            <span class="kt-widget24__change">

                                Accepted || Rejected

                            </span>

                            <span class="kt-widget24__number">

                                {{ $final_data['rebuttal']['accepted'] }} - {{ $final_data['rebuttal']['accepted_per'] }}%
                                || {{ $final_data['rebuttal']['rejected'] }} -
                                {{ $final_data['rebuttal']['accepted_per'] }}%

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!--end:: Widgets/Stats-->

    <div class="row">

        <div class="col-xl-6">



            <!--begin:: Widgets/Personal Income-->

            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">

                <div class="kt-portlet__head kt-portlet__space-x">

                    <div class="kt-portlet__head-label">

                        <h3 class="kt-portlet__head-title kt-font-light">

                            LOB Wise Score

                        </h3>

                    </div>



                </div>

                <div class="kt-portlet__body">

                    <div class="kt-widget27">

                        <div class="kt-widget27__visual">

                            <img src="/assets/media//bg/bg-4.jpg" alt="" style="height: 80px;">

                        </div>

                        <div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">



                            <ul class="nav nav-pills nav-fill" role="tablist">

                                <?php $active = 0;
                                
                                $first_process = 0; ?>

                                @foreach ($final_data['pws'] as $key => $value)
                                    <?php if ($active == 0) {
                                        $first_process = $key;
                                    } ?>

                                    <li class="nav-item">

                                        <a class="nav-link <?php if ($active == 0) {
                                            echo ' active';
                                        } ?>" data-toggle="pill"
                                            href="#kt_personal_income_quater_{{ $key }}"
                                            onclick="return qrc_dynamic({{ $key }});">{{ $value['name'] }}</a>

                                    </li>

                                    <?php $active++; ?>
                                @endforeach

                            </ul>



                            <div class="tab-content">

                                <?php $active2 = 0; ?>

                                @foreach ($final_data['pws'] as $key => $value)
                                    <div id="kt_personal_income_quater_{{ $key }}"
                                        class="tab-pane <?php if ($active2 == 0) {
                                            echo ' active';
                                        } ?>">

                                        <div class="kt-widget11">

                                            <div class="table-responsive">



                                                <!--begin::Table-->

                                                <table class="table">



                                                    <!--begin::Thead-->

                                                    <thead>

                                                        <tr>

                                                            <td>Head</td>

                                                            <td class="kt-align-right">Value</td>

                                                        </tr>

                                                    </thead>



                                                    <!--end::Thead-->



                                                    <!--begin::Tbody-->

                                                    <tbody>

                                                        <tr>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Audit
                                                                    Count</a>

                                                            </td>

                                                            <td class="kt-align-right">{{ $value['data']['audit_count'] }}
                                                            </td>

                                                        </tr>

                                                        <tr>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title"
                                                                    style="color: rgb(65, 105, 225);">Main Score (With
                                                                    fatal)</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold"
                                                                style="color: rgb(65, 105, 225);">
                                                                {{ $value['data']['scored_with_fatal'] }}%</td>

                                                        </tr>

                                                        <tr>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Score Excl.
                                                                    Fatal Computation</a>

                                                            </td>

                                                            <td class="kt-align-right">{{ $value['data']['score'] }}%</td>

                                                        </tr>

                                                        <!-- New added by shailendra pms ticket - 945 -->

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title"
                                                                    style="color:#ffb822">Total Rebuttal Raised</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-warning">
                                                                {{ $value['data']['raised_process'] }} -
                                                                {{ $value['data']['rebuttal_per'] }}%</td>

                                                        </tr>

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Rebuttal
                                                                    Accepted</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold">
                                                                {{ $value['data']['accepted_process'] }} -
                                                                {{ $value['data']['accepted_per'] }}%</td>

                                                        </tr>

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Rebuttal
                                                                    Rejected</a>

                                                            </td>

                                                            <td class="kt-align-right">
                                                                {{ $value['data']['rejected_process'] }} -
                                                                {{ $value['data']['rejected_per'] }}%</td>

                                                        </tr>

                                                        <!-- New added by shailendra pms ticket - 945 -->

                                                    </tbody>



                                                    <!--end::Tbody-->

                                                </table>



                                                <!--end::Table-->

                                            </div>

                                        </div>

                                    </div>

                                    <?php $active2++; ?>
                                @endforeach





                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!--end:: Widgets/Personal Income-->

        </div>

        <div class="col-xl-6">

            <!--begin:: Widgets/Personal Income-->

            <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">

                <div class="kt-portlet__head kt-portlet__space-x">

                    <div class="kt-portlet__head-label">

                        <h3 class="kt-portlet__head-title kt-font-light">

                            Call Type : As Per System

                        </h3>

                    </div>



                </div>

                <div class="kt-portlet__body">

                    <div class="kt-widget27">

                        <div class="kt-widget27__visual">

                            <img src="/assets/media//misc/bg-2.jpg" alt="" style="height: 80px;">

                        </div>

                        <div class="kt-widget27__container kt-portlet__space-x" style="margin:0;">

                            <ul class="nav nav-pills nav-fill" role="tablist">

                                <?php $s = 0;
                                $count = 1; ?>





                                @foreach ($final_data['call_type'] as $ct)
                                    <li class="nav-item">

                                        <?php if($client_id == 1) {

							if($count <= 2){

								?>

                                        <a <?php if($s == 0) { ?> class="nav-link active" <?php } else { ?>
                                            class="nav-link" <?php } ?> data-toggle="pill" <?php if($s == 0) { ?>
                                            href="#kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?>
                                            href="#kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?>
                                            href="#kt_personal_income_quater_qrc_c"
                                            <?php }  ?>>{{ $ct }}</a>

                                        <?php

							}

						} else{

							?>

                                        <a <?php if($s == 0) { ?> class="nav-link active" <?php } else { ?>
                                            class="nav-link" <?php } ?> data-toggle="pill" <?php if($s == 0) { ?>
                                            href="#kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?>
                                            href="#kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?>
                                            href="#kt_personal_income_quater_qrc_c"
                                            <?php }  ?>>{{ $ct }}</a>

                                        <?php

						}



						?>



                                    </li>

                                    <?php
                                    
                                    $count++;
                                    
                                    $s++;
                                    
                                    ?>
                                @endforeach



                            </ul>

                            <div class="tab-content">

                                <?php $s = 0; ?>

                                @foreach ($final_data['call_type'] as $ct)
                                    <div <?php if($s == 0) { ?> id="kt_personal_income_quater_qrc_q" <?php } if($s == 1) { ?>
                                        id="kt_personal_income_quater_qrc_r" <?php } if($s == 2) { ?>
                                        id="kt_personal_income_quater_qrc_c" <?php } if($s == 0) { ?> class="tab-pane active"
                                        <?php } else { ?> class="tab-pane fade" <?php } ?>>

                                        <div class="kt-widget11">

                                            <div class="table-responsive">



                                                <!--begin::Table-->

                                                <table class="table">



                                                    <!--begin::Thead-->

                                                    <thead>

                                                        <tr>

                                                            <td>Head</td>

                                                            <td class="kt-align-right">Count</td>

                                                        </tr>

                                                    </thead>



                                                    <!--end::Thead-->



                                                    <!--begin::Tbody-->

                                                    <tbody>

                                                        <tr>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Audit
                                                                    Count</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold"
                                                                id="<?php if ($s == 0) {
                                                                    echo 'query_count';
                                                                }
                                                                if ($s == 1) {
                                                                    echo 'request_count';
                                                                }
                                                                if ($s == 2) {
                                                                    echo 'complaint_count';
                                                                } ?>"></td>

                                                        </tr>

                                                        <tr>

                                                            <td>

                                                                <a href="#"
                                                                    class="kt-widget11__title kt-font-danger">FATAL
                                                                    Count</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold kt-font-danger"
                                                                id="<?php if ($s == 0) {
                                                                    echo 'query_fatal_count';
                                                                }
                                                                if ($s == 1) {
                                                                    echo 'request_fatal_count';
                                                                }
                                                                if ($s == 2) {
                                                                    echo 'complaint_fatal_count';
                                                                } ?>">



                                                            </td>

                                                        </tr>

                                                        <!-- New added by shailendra pms ticket - 945 -->

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title"
                                                                    style="color:#ffb822">Total Rebuttal Raised</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold kt-font-warning"
                                                                style="color:#ffb822" id="<?php if ($s == 0) {
                                                                    echo 'query_rebuttal_per';
                                                                }
                                                                if ($s == 1) {
                                                                    echo 'request_rebuttal_per';
                                                                }
                                                                if ($s == 2) {
                                                                    echo 'complain_rebuttal_per';
                                                                } ?>">



                                                            </td>

                                                        </tr>

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Rebuttal
                                                                    Accepted</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold"
                                                                id="<?php if ($s == 0) {
                                                                    echo 'query_accepted_per';
                                                                }
                                                                if ($s == 1) {
                                                                    echo 'request_accepted_per';
                                                                }
                                                                if ($s == 2) {
                                                                    echo 'complain_accepted_per';
                                                                } ?>">



                                                            </td>

                                                        </tr>

                                                        <tr hidden>

                                                            <td>

                                                                <a href="#" class="kt-widget11__title">Rebuttal
                                                                    Rejected</a>

                                                            </td>

                                                            <td class="kt-align-right kt-font-brand kt-font-bold"
                                                                id="<?php if ($s == 0) {
                                                                    echo 'query_rejected_per';
                                                                }
                                                                if ($s == 1) {
                                                                    echo 'request_rejected_per';
                                                                }
                                                                if ($s == 2) {
                                                                    echo 'complain_rejected_per';
                                                                } ?>">

                                                            </td>

                                                        </tr>

                                                        <!-- New added by shailendra pms ticket - 945 -->

                                                    </tbody>



                                                    <!--end::Tbody-->

                                                </table>



                                                <!--end::Table-->

                                            </div>

                                        </div>

                                    </div>

                                    <?php $s++; ?>
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!--end:: Widgets/Personal Income-->

        </div>



    </div>



    <div class="row">

        <div class="kt-portlet kt-portlet--mobile col-md-12">

            <div class="kt-portlet__head">

                <div class="kt-portlet__head-label">

                    <h3 class="kt-portlet__head-title" style="color:white">

                        Process Score

                    </h3>

                </div>

            </div>

            <div class="kt-portlet__body">

                <div class="row">

                    <div class="col-md-12">

                        <div class="row">

                            <div class="col-md-6">

                                <h5 align="center" style="color:#4169E1">With Fatal</h5>

                                <div id="with_fatel"></div>

                            </div>

                            <div class="col-md-6">

                                <h5 align="center" style="color:grey">Without Fatal</h5>

                                <div id="without_fatel"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>



        <div class="col-md-12">

            <div class="kt-portlet kt-portlet--mobile col-md-12">

                <div class="kt-portlet__head">

                    <div class="kt-portlet__head-label">

                        <h3 class="kt-portlet__head-title" style="color:white">

                            Parameter Wise Compliance

                        </h3>

                    </div>

                </div>

                <div class="kt-portlet__body">

                    <div class="row">

                        <div class="col-md-12">

                            <div id="paramerer_wise_compilance"> </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>



        <div class="col-md-12">

            <div class="kt-portlet kt-portlet--mobile col-md-12">

                <div class="kt-portlet__head">

                    <div class="kt-portlet__head-label">

                        <h3 class="kt-portlet__head-title" style="color:white">

                            Pareto Analysis

                        </h3>

                    </div>

                </div>

                <div class="kt-portlet__body">

                    <div class="row">

                        <div class="col-md-12">

                            <div id="pareto_data"> </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- @if (!Auth::user()->hasRole('partner-admin'))
            <div class="col-md-6">

                <div class="kt-portlet kt-portlet--mobile col-md-12">

                    <div class="kt-portlet__head">

                        <div class="kt-portlet__head-label">

                            <h3 class="kt-portlet__head-title">

                                Top 10 Agents

                            </h3>

                        </div>

                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">

                            <div class="col-md-12">

                              

                                <table class="table table-striped- table-bordered table-hover table-checkable"
                                    id="kt_table_1">

                                    <thead>

                                        <tr>



                                            <th>Agent</th>

                                            <th>Audit Count</th>



                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $m = 0; ?>

                                        <?php
                                        
                                        $count = 0;
                                        
                                        ?>

                                        @foreach ($final_data['plr'] as $p)
                                            @if ($p['audit_count'] != 0 and $count < 10)
                                                <tr>

                                                    <td>{{ $p['partner_name'] }}</td>

                                                    <td>{{ $p['audit_count'] }}</td>



                                                </tr>

                                                <tr>

                                                    <td id="pro_<?= $m ?>" style="display: none;">

                                                        <table>

                                                            <thead>

                                                                <tr>

                                                                    <th>Process</th>

                                                                    <th>Location</th>

                                                                    <th>Audit Count</th>



                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                                @foreach ($p['process_data'] as $pro)
                                                                    <tr>

                                                                        <td>{{ $pro['process_name'] }}</td>

                                                                        <td>{{ $pro['location'] }}</td>

                                                                        <td>{{ $pro['audit_count'] }}</td>



                                                                    </tr>
                                                                @endforeach

                                                            </tbody>

                                                        </table>

                                                    </td>

                                                </tr>

                                                <?php $count++; ?>
                                            @endif

                                            <?php $m++;
                                            
                                            ?>
                                        @endforeach



                                    </tbody>

                                </table>



                              

                            </div>

                        </div>

                    </div>

                </div>



            </div>



            <div class="col-md-6">

                <div class="kt-portlet kt-portlet--mobile col-md-12">

                    <div class="kt-portlet__head">

                        <div class="kt-portlet__head-label">

                            <h3 class="kt-portlet__head-title">

                                Bottom 10 Agents

                            </h3>

                        </div>

                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">

                            <div class="col-md-12">

                           

                                <table class="table table-striped- table-bordered table-hover table-checkable"
                                    id="kt_table_1">

                                    <thead>

                                        <tr>



                                            <th>Agent</th>

                                            <th>Audit Count</th>



                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $m = 0;
                                        
                                        $count = 0;
                                        
                                        $array_size = sizeof($final_data['plr']);
                                        
                                        $minimum = $array_size - 10;
                                        
                                        ?>

                                        @foreach ($final_data['plr'] as $p)
                                            @if ($count >= $minimum)
                                                <tr>

                                                    <td> {{ $p['partner_name'] }}</td>

                                                    <td>{{ $p['audit_count'] }}</td>



                                                </tr>

                                                <tr>

                                                    <td id="pro_<?= $m ?>" style="display: none;">

                                                        <table>

                                                            <thead>

                                                                <tr>

                                                                    <th>Process</th>

                                                                    <th>Location</th>

                                                                    <th>Audit Count</th>



                                                                </tr>

                                                            </thead>

                                                            <tbody>

                                                                @foreach ($p['process_data'] as $pro)
                                                                    <tr>

                                                                        <td>{{ $pro['process_name'] }}</td>

                                                                        <td>{{ $pro['location'] }}</td>

                                                                        <td>{{ $pro['audit_count'] }}</td>



                                                                    </tr>
                                                                @endforeach

                                                            </tbody>

                                                        </table>

                                                    </td>

                                                </tr>
                                            @endif

                                            <?php $m++;
                                            
                                            $count++;
                                            
                                            ?>
                                        @endforeach



                                    </tbody>

                                </table>



                              
                            </div>

                        </div>

                    </div>

                </div>



            </div>
        @endif -->





    </div>

@endsection

@section('js')
    <script type="application/javascript"> 

	// Fatal Chart start 

	var gaugeOptions = {

		chart: {

			type: 'solidgauge'

		},

		title: null,

		exporting: {

			enabled: false

		},

		tooltip: {

			enabled: false

		},

		// the value axis

		yAxis: {

			stops: [

				[0.1, '#ef600ccc'], // green

				[0.5, '#ef600ccc'], // yellow

				[0.9, '#ef600ccc'] // red

			]

		}

	};



	var gaugeOptionstwo = {

		chart: {

			type: 'solidgauge'

		},

		title: null,

		exporting: {

			enabled: false

		},

		tooltip: {

			enabled: false

		},

		// the value axis

		yAxis: {

			stops: [

				[0.1, '#103264'], // green

				[0.5, '#103264'], // yellow

				[0.9, '#103264'] // red

			]

		}

	};



	var data = [];

	// Fatal Chart start 

	var with_fatal_guage_chart = Highcharts.chart('with_fatel', 

		Highcharts.merge(gaugeOptions,{

			chart: {

				type: 'solidgauge',

				height:280,

			},

			title: null,

			pane: {

				center: ['50%', '70%'],

				size: '100%',

				startAngle: -90,

				endAngle: 90,

				background: {

				innerRadius: '60%',

				outerRadius: '100%',

				shape: 'arc'

			}

		},



		tooltip: {

		enabled: false

		},



		// the value axis

		yAxis: {



			lineWidth: 0,

			minorTickInterval: null,

			tickAmount: 2,

			title: {

				y: -0

			},

			labels: {

				y: 0

			}

		},



		plotOptions: {

			solidgauge: {

				dataLabels: {

					y: 6,

					borderWidth: 0,

					useHTML: true

				}

			}

		},

		yAxis: {

			min: 0,

			max: 100,

			title: {

				text: ''

			}

		},

		credits: {

			enabled: false

		},

		series: [{

			name: 'Speed',

			data: data,

			dataLabels: {

			format:

				'<div style="text-align:center">' +

				'<span style="font-size:25px">{y}%</span><br/>' +

				'<span style="font-size:12px;opacity:0.4">Score</span>' +

				'</div>'

			},

				tooltip: {

				valueSuffix: ' Count'

			}

		}]

	}));



	var without_fatal_guage_chart = Highcharts.chart('without_fatel', 

		Highcharts.merge(gaugeOptionstwo,{

			chart: {

				type: 'solidgauge',

				height:280,

			},

			title: null,

			pane: {

				center: ['50%', '70%'],

				size: '100%',

				startAngle: -90,

				endAngle: 90,

				background: {

					innerRadius: '60%',

					outerRadius: '100%',

					shape: 'arc'

				}

			},



			tooltip: {

				enabled: false

			},



			// the value axis

			yAxis: {



				lineWidth: 0,

				minorTickInterval: null,

				tickAmount: 2,

				title: {

					y: -0

				},

				labels: {

					y: 0

				}

			},



			plotOptions: {

				solidgauge: {

					dataLabels: {

						y: 6,

						borderWidth: 0,

						useHTML: true

					}

				}

			},

			yAxis: {

				min: 0,

				max: 100,

				title: {

					text: ''

				}

			},



			credits: {

				enabled: false

			},



			series: [{

				name: 'Speed',

				data: data,

				dataLabels: {

					format:

					'<div style="text-align:center">' +

					'<span style="font-size:25px">{y}%</span><br/>' +

					'<span style="font-size:12px;opacity:0.4">Score</span>' +

					'</div>'

				},

				tooltip: {

					valueSuffix: ' Score'

				}

			}]



		}

	));



	// Parameter wise score chart



	var paramerer_wise_compilance = Highcharts.chart('paramerer_wise_compilance', {



		chart: {

			type: 'spline'

		},



		title: {

			text: ''

		},

		subtitle: {

			text: ''

		},

		xAxis: {

			categories: ['First','Second','Third','Fourth','fifth','sixth']

		},

		yAxis: {

			min:0,

			max:100,

			title: {

				text: '%'

			},

			labels: {

				format: '{value} %',

				style: {

					

				}

			}

		},

		plotOptions: {

			line: {

				dataLabels: {

					enabled: true,

					format: '{point.y:.0f}%'

				},

				enableMouseTracking: false

			}

		},

		series: [{

					name: 'Compliance %',

					data: [10,10,10,10,10,10],

					color: '#103264'



		}]



	});



	// Pareto Chart

	var pareto_data = Highcharts.chart('pareto_data', {

		chart: {

			zoomType: 'xy'

		},

		title: {

			text: ''

		},

		subtitle: {

			text: ''

		},

		xAxis: [{

			categories:['Reason1', 'Reason2','Reason3'],

			crosshair: true

		}],

		yAxis: [

			{ // Primary yAxis

				min:0,

				max:100,

				labels: {

					format: '{value} %',

					style: {



					}

				},

				title: {

					text: '%',

					style: {



					}

				}

				}, { // Secondary yAxis

				title: {

					text: 'Count',

					style: {



					}

				},

				labels: {

					format: '{value}',

					style: {



					}

				},

				opposite: true

			}

		],

		tooltip: {

			shared: true

		},

		legend: {

			layout: 'horizontal',

			align: 'left',

			x: 0,

			verticalAlign: 'top',

			y: 0,

			floating: true,

		},

		series: [{

			name: 'Reason Counts',

			type: 'column',

			yAxis: 1,

			data: [7,6,5],

			color: '#ef600ccc',

			tooltip: {

				valueSuffix: ' Count'

			}



			}, {

				name: 'Percentage',

				type: 'spline',

				data:[15.910000000000000142108547152020037174224853515625,29.550000000000000710542735760100185871124267578125,40.909999999999996589394868351519107818603515625],

				color: '#103264',

				tooltip: {

				valueSuffix: '%'

				}

			}

		]

	});



</script>



    <script>
        function myFunction(x) {

            x.classList.toggle("minus");

        }



        function getTable(id) {

            $("#pro_" + id).toggle();

        }

        (function() {



            /* $('#cover-spin').show(0); */

            var withfatalscore;

            var base_url = window.location.origin;

            $.ajax({

                type: "GET",

                url: base_url +
                    "/agent/get_qrc_lob_wise_welcome_dashboard/{{ $first_process }}/{{ $month_first_data }}/{{ $today }}",

                success: function(Data) {





                    $("#query_count").html(Data.qrc.query_count);

                    $("#query_fatal_count").html(Data.qrc.query_fatal_count);

                    $("#query_rebuttal_per").html(Data.qrc.query_raised_process + " - " + Data.qrc
                        .query_rebuttal_per + "%");

                    $("#query_accepted_per").html(Data.qrc.query_accepted_process + " - " + Data.qrc
                        .query_accepted_per + "%");

                    $("#query_rejected_per").html(Data.qrc.query_rejected_process + " - " + Data.qrc
                        .query_rejected_per + "%");



                    $("#request_count").html(Data.qrc.request_count);

                    $("#request_fatal_count").html(Data.qrc.request_fatal_count);

                    $("#request_rebuttal_per").html(Data.qrc.request_raised_process + " - " + Data.qrc
                        .request_rebuttal_per + "%");

                    $("#request_accepted_per").html(Data.qrc.request_accepted_process + " - " + Data.qrc
                        .request_accepted_per + "%");

                    $("#request_rejected_per").html(Data.qrc.request_rejected_process + " - " + Data.qrc
                        .request_rejected_per + "%");





                    $("#complaint_count").html(Data.qrc.complaint_count);

                    $("#complaint_fatal_count").html(Data.qrc.complaint_fatal_count);

                    $("#complain_rebuttal_per").html(Data.qrc.complain_raised_process + " - " + Data.qrc
                        .complain_rebuttal_per + "%");

                    $("#complain_accepted_per").html(Data.qrc.complain_accepted_process + " - " + Data.qrc
                        .complain_accepted_per + "%");

                    $("#complain_rejected_per").html(Data.qrc.complain_rejected_process + " - " + Data.qrc
                        .complain_rejected_per + "%");



                    // Guage fatal chart

                    with_fatal_guage_chart.series[0].setData([Data.fatal_dialer_data.with_fatal_score]);

                    without_fatal_guage_chart.series[0].setData([Data.fatal_dialer_data
                        .without_fatal_score]);

                    // Guage fatal chart

                    // Parameter compliance 

                    //console.log(Data.parameter_wise_score);



                    paramerer_wise_compilance.series[0].setData(Data.parameter_wise_score);

                    paramerer_wise_compilance.xAxis[0].setCategories(Data.parameter_list);

                    // Parameter compliance

                    // Pareto Chart

                    pareto_data.series[0].setData(Data.pareto_data.count);

                    pareto_data.series[1].setData(Data.pareto_data.per);

                    pareto_data.xAxis[0].setCategories(Data.pareto_data.reasons);

                    //Pareto Chart

                }

            });



            // Guage Charts



        })();





        function qrc_dynamic(val) {

            /* $('#cover-spin').show(0); */



            var base_url = window.location.origin;

            $.ajax({

                type: "GET",

                url: base_url + "/agent/get_qrc_lob_wise_welcome_dashboard/" + val +
                    "/{{ $month_first_data }}/{{ $today }}",

                success: function(Data) {



                    //console.log(Data.qrc.query_count);



                    $("#query_count").html(Data.qrc.query_count);

                    $("#query_fatal_count").html(Data.qrc.query_fatal_count);

                    $("#query_rebuttal_per").html(Data.qrc.query_raised_process + " - " + Data.qrc
                        .query_rebuttal_per + "%");

                    $("#query_accepted_per").html(Data.qrc.query_accepted_process + " - " + Data.qrc
                        .query_accepted_per + "%");

                    $("#query_rejected_per").html(Data.qrc.query_rejected_process + " - " + Data.qrc
                        .query_rejected_per + "%");



                    $("#request_count").html(Data.qrc.request_count);

                    $("#request_fatal_count").html(Data.qrc.request_fatal_count);

                    $("#request_rebuttal_per").html(Data.qrc.request_raised_process + " - " + Data.qrc
                        .request_rebuttal_per + "%");

                    $("#request_accepted_per").html(Data.qrc.request_accepted_process + " - " + Data.qrc
                        .request_accepted_per + "%");

                    $("#request_rejected_per").html(Data.qrc.request_rejected_process + " - " + Data.qrc
                        .request_rejected_per + "%");





                    $("#complaint_count").html(Data.qrc.complaint_count);

                    $("#complaint_fatal_count").html(Data.qrc.complaint_fatal_count);

                    $("#complain_rebuttal_per").html(Data.qrc.complain_raised_process + " - " + Data.qrc
                        .complain_rebuttal_per + "%");

                    $("#complain_accepted_per").html(Data.qrc.complain_accepted_process + " - " + Data.qrc
                        .complain_accepted_per + "%");

                    $("#complain_rejected_per").html(Data.qrc.complain_rejected_process + " - " + Data.qrc
                        .complain_rejected_per + "%");



                    // Guage fatal chart

                    with_fatal_guage_chart.series[0].setData([Data.fatal_dialer_data.with_fatal_score]);

                    without_fatal_guage_chart.series[0].setData([Data.fatal_dialer_data.without_fatal_score]);

                    // Guage fatal chart

                    // Parameter compliance 

                    paramerer_wise_compilance.series[0].setData(Data.parameter_wise_score);

                    paramerer_wise_compilance.xAxis[0].setCategories(Data.parameter_list);

                    // Parameter compliance

                    // Pareto Chart

                    pareto_data.series[0].setData(Data.pareto_data.count);

                    pareto_data.series[1].setData(Data.pareto_data.per);

                    pareto_data.xAxis[0].setCategories(Data.pareto_data.reasons);

                    //Pareto Chart

                }

            });

            return true;

        }



        function getCycle(val) {



            var base_url = window.location.origin;

            if (val != 0) {

                $.ajax({

                    type: "GET",

                    url: base_url + "/dashboard/get_partner_audit_cycle/" + val,

                    success: function(Data) {

                        $("#audit_cycle").html(Data);

                    }

                });





            }

        }

        function getLocation(val) {



            var base_url = window.location.origin;

            if (val != 0) {

                $.ajax({

                    type: "GET",

                    url: base_url + "/dashboard/get_partner_locations1/" + val,

                    success: function(Data) {

                        $("#location").html(Data + '<option value="%">All</option>');

                    }

                });



                $.ajax({

                    type: "GET",

                    url: base_url + "/dashboard/get_partner_process1/" + val,

                    success: function(Data) {

                        $("#process").html(Data);

                    }

                });



                $.ajax({

                    type: "GET",

                    url: base_url + "/dashboard/get_partner_lob/" + val,

                    success: function(Data) {

                        $("#lob").html(Data + '<option value="%">All</option>');

                    }

                });

            }

        }



        function check_cycle() {

            var cycle = document.getElementById("audit_cycle").value;

            if (cycle == "blank") {

                document.getElementById("warning").style.display = "block";

                //document.getElementById("audit_cycle").value

                return false;

            } else {

                return true;

            }

            //console.log(cycle);



        }
    </script>



    <script>
        $(function() {

            $("#datepicker123").daterangepicker({

                opens: 'right'

            }, function(start, end, label) {

                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));

            });

        });
    </script>
@endsection

@section('css')
    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
