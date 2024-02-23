@extends('porter_design.layouts.app')

@section('main')
<div class="container-fluid">
            <div class="tabFatalk mb-3">
                <h2 class="tittle fs-5 mb-4 fw-bold">Audit Of Audit Scores Summary</h2>
                <div class="table-responsive ">
                    <table class="responsive-table table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Auditor ID</th>
                                <th class="text-center">Total Audits</th>
                                <th class="text-center">Total AOA Count</th>
                                <th class="text-center">Error</th>
                                <th class="text-center">No Error</th>
                                <th class="text-center">BOD</th>
                                <th class="text-center">Accuracy%</th>
                                <th class="text-center">Inaccuracy%</th>
                            </tr>
                        </thead>
                        <tbody id="table_body2">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tabFatalk mb-3">
                <h2 class="tittle fs-5 mb-4 fw-bold">Call Calibration score</h2>
                <div class="table-responsive ">
                    <table class="responsive-table table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>PFE Auditors Name</th>
                                <th class="text-center">Calls Calibrated</th>
                                <th class="text-center">Error found</th>
                                <th class="text-center">No Error</th>
                                <th class="text-center">BOD given</th>
                                <th class="text-center">Calibration Accuracy</th>
                            </tr>
                        </thead>
                        <tbody id="table_body3">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tabFatalk mb-3">
                <h2 class="tittle fs-5 mb-4 fw-bold">Rebuttal score</h2>
                <div class="table-responsive ">
                    <table class="responsive-table table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Auditor ID</th>
                                <th class="text-center">Total Audits</th>
                                <th class="text-center">Rebuttals Raised</th>
                                <th class="text-center">Valid Rebuttals</th>
                                <th class="text-center">Auditor Error</th>
                                <th class="text-center">No Error</th>
                                <th class="text-center">BOD Given</th>
                                <th class="text-center">Rebuttal Accepted%</th>
                                <th class="text-center">Overall Audits Rebuttal Accepted</th>

                            </tr>
                        </thead>
                        <tbody id="table_body">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tabFatalk mb-3">
                <h2 class="tittle fs-5 mb-4 fw-bold">Quality Scores</h2>
                <div class="table-responsive ">
                    <table class="responsive-table table table-striped" width="100%">
                        <thead>
                                <th>Date</th>
                                <th class="text-center">Audit count</th>
                                <th class="text-center">Agent count</th>
                                <th class="text-center">Audit score</th>
                                <th class="text-center">Fatal counts</th>
                                <th class="text-center">Fatal %</th>
                        </thead>
                        <tbody id="table_body1">
                        
                        </tbody>
                    </table>
                </div>
               
            </div>
            </div>
    <!-- modal start  -->
@endsection


{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script> --}}


@section('js')
@include('porter_design.shared.agent_dashbaord_js')
<script>
    rebuttal_score("2023-11-01", "2024-01-31")  
    quality_score("2023-11-01", "2024-01-31")  
    qa_score_summary("2023-11-01", "2024-01-31")  
    call_calibration_score("2023-11-01", "2024-01-31")  
    function rebuttal_score(start_date, end_date){
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/qa_dashboard/rebuttal_score/"+start_date+"/"+end_date,
            success: function(Data) {
                console.log(Data.data)
                $("#table_body").append(Data.data.html);
            }
        });
    }
    function quality_score(start_date, end_date){
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/qa_dashboard/quality_score/"+start_date+"/"+end_date,
            success: function(Data) {
                console.log(Data.data)
                $("#table_body1").append(Data.data.html);
            }
        });
    }
    function qa_score_summary(start_date, end_date){
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/qa_dashboard/qa_score_summary/"+start_date+"/"+end_date,
            success: function(Data) {
                console.log(Data.data)
                $("#table_body2").append(Data.data.html);
            }
        });
    }
    function call_calibration_score(start_date, end_date){
        var base_url = window.location.origin;
        $.ajax({
            type: "GET",
            url: base_url + "/qa_dashboard/call_calibration_score/"+start_date+"/"+end_date,
            success: function(Data) {
                console.log(Data.data)
                $("#table_body3").append(Data.data.html);
            }
        });
    }
    
    
        Highcharts.chart('scoreChrt', {
            chart: {
                type: 'bar',
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Communication', 'Process', 'System'],
                title: {
                    enabled: false
                },
                gridLineWidth: 1,
                lineWidth: 1, // Set the width of the x-axis line
                lineColor: '#e6e6e6',
            },
            yAxis: {
                min: 0,
                labels: {
                    overflow: 'justify',
                    formatter: function () {
                        return this.value + '%'; // Add a percent symbol after the y-axis values
                    }
                },
    
                // gridLineWidth: 1,
                opposite: true,
            },
            tooltip: {
                valueSuffix: ' %'
            },
            plotOptions: {
                bar: {
                    borderRadius: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '%'; // Add a percent symbol after the y-axis values
                        }
                    },
                    title: {
                        enabled: false
                    },
                    groupPadding: .2,
                    // Apply gradient color to the bars
                    color: {
                        linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
                        stops: [
                            [0, '#7467F0'],   // End color
                            [1, '#2896E9'],  // Start color
                        ]
                    }
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Score',
                data: [87.50, 92.85, 80.40]  // Reduce the value for the first bar to 400
            }]
        });
    
    
    </script>
@endsection
