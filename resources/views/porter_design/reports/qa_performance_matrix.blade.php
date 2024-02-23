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
                'route' => 'qa_performance_matrix', 
                'class' => 'kt-form',
                'role'=>'form',
                'data-toggle'=>"validator",
                'enctype'=>'multipart/form-data')
                ) !!}

            <div class="kt-portlet__body">
                <div class="row">
                    <div class="d-flex align-items-center gap-3">
                        <label for="" class="mb-0 fw-bold text-primary">Datess:</label>
                        <div class="position-relative">
                            <input type="month" id="demo-1" name = "target_month_new"  class="form-control" value=""
                                placeholder="20-3-2012 to 12-2-3023">
                            <img src="/assets/design/img/Icon awesome-`calendar-alt.svg" class="calenderIcon"
                                alt="calendaricon">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                </div>
            </div><br>
            <div class="kt-portlet__foot">
            </div>
            </form>
            <?php 
			$rank = array();
			foreach($data as $row) {
				
				$audit_target = $row->qa_target;
				$audit_achievement = $row->audits;
				$audit_achievement_percent = 0;
				$audit_score = 0;
				$audit_bonus = 0;

				if($row->qa_target != 0) {
					$audit_achievement_percent = number_format(($row->audits/$row->qa_target) * 100, 2, '.', ',');
					$audit_score = number_format( (($row->audits/$row->qa_target )* 100*35)/100, 2, '.', ',');
					
					$audit_bonus = ($audit_score - 35) * 100/35;
					if($audit_bonus >= 5){
						$audit_bonus = 5;
					}
					else {
						$audit_bonus = 0;
					}
			
				}

				$qc_audits = $row->qc_audits;
				$qc_deviation = 0;
				$qc_score_score = 0;
				$qc_bonus_bonus = 0;

				if($row->audits != 0){
					$qc_deviation = number_format(($row->qc_audits/ $row->audits) * 100, 2, '.', ',');
					
					if($qc_deviation <= 5){
						$qc_score_score = 15;
						if($qc_deviation == 0) {
							$qc_bonus_bonus = 5;
						}else {
							$qc_bonus_bonus = 0;
						}
					}
					else{
						$qc_score_score = 0;
					}
				}

				$rebuttal_accept = $row->rebuttal_accept;
				$rebuttal_accept_percent = 0;
				$rebuttal_score = 0;
				$rebuttal_bonus = 0;

				if($row->audits != 0){
					$rebuttal_accept_percent = number_format(($row->rebuttal_accept/ $row->audits) * 100, 2, '.', ',');
					
					if($rebuttal_accept_percent <= 2){
						$rebuttal_score = 20;
						if($rebuttal_accept_percent == 0) {
							$rebuttal_bonus = 5;
						}else {
							$rebuttal_bonus = 0;
						}
					}
					else{
						$rebuttal_score = 0;
					}
				}

				$training_pkt = $row->training_pkt;
				$training_pkt_score = 0;
				$training_pkt_bonus = 0;

				
				if($training_pkt > 85){
					$training_pkt_score = 10;
					if($training_pkt == 100) {
						$training_pkt_bonus = 5;
					}else {
						$training_pkt_bonus = 0;
					}
				}
				else{
					$training_pkt_score = 0;
				}
				
				$calibration = 0;
				$calibration_score = 0;
				$calibration_bonus = 0;

				$rank[$row->id] = $audit_score + $audit_bonus + $qc_score_score + $qc_bonus_bonus + $rebuttal_score + $rebuttal_bonus + $training_pkt_score + $training_pkt_bonus + $calibration_score + $calibration_bonus;

			}
			
			arsort($rank);

			
		?>
            <table class="table mb-0 datatables">
                <thead>
                    <tr>
                        <th title="Field #1">#</th>
                        <th title="Field #1">
                            Name
                        </th>
    
                        <th title="Field #1">
                            Audit Target score
                        </th>
                        <th title="Field #1">
                            QC Deviation score
                        </th>
                        <th title="Field #1">
                            Rebuttal score 
                        </th>
                        <th title="Field #2">
                            Training PKT sore
                        </th>
    
                        <th title="Field #2">
                            Internal call calibration Score
                        </th>
                        <th title="Field #2">
                            Total Score
                        </th>
                        <th title="Field #2" class = "hide_1">
                            Total Bonus
                        </th>
                        <th title="Field #2">
                            Total Score with bonus
                        </th>
                        <th title="Field #2">
                            Rank
                        </th>
                        <th title="Field #2">
                            Audit Target 
                        </th>
                        <th title="Field #2">
                            Audit Target Achievement
                        </th>
                        <th title="Field #2">
                            Audit Target Achievement %
                        </th>
                        
                        <th title="Field #2">
                        Audit Target Bonus
                        </th>
                        <th title="Field #2">
                            QC Deviation
                        </th>
                        
                        <th title="Field #2">
                            QC Deviation bonus
                        </th>
                        <th title="Field #2" class = "hide_1">
                            Rebuttal Accepted
                        </th>
                        <th title="Field #2">
                            Rebuttal Accepted %
                        </th>
                        
                        <th title="Field #2">
                            Rebuttal bonus
                        </th>
                        <th title="Field #2">
                            Training PKT 
                        </th>
                        
                        <th title="Field #2">
                            Training PKT bonus
                        </th>
                        <th title="Field #2">
                            Internal call calibration deviation
                        </th>
                        
                        <th title="Field #2">
                            Internal call calibration Bonus
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
                        
                        <?php 
                            $audit_target = $row->qa_target;
                            $audit_achievement = $row->audits;
                            $audit_achievement_percent = 0;
                            $audit_score = 0;
                            $audit_bonus = 0;
    
                            if($row->qa_target != 0) {
                                $audit_achievement_percent = number_format(($row->audits/$row->qa_target) * 100, 2, '.', ',');
                                $audit_score = number_format( (($row->audits/$row->qa_target )* 100*35)/100, 2, '.', ',');
                                
                                $audit_bonus = ($audit_score - 35) * 100/35;
                                if($audit_bonus >= 5){
                                    $audit_bonus = 5;
                                }
                                else {
                                    $audit_bonus = 0;
                                }
                        
                            }
    
                            $qc_audits = $row->qc_audits;
                            $qc_deviation = 0;
                            $qc_score_score = 0;
                            $qc_bonus_bonus = 0;
    
                            if($row->audits != 0){
                                $qc_deviation = number_format(($row->qc_audits/ $row->audits) * 100, 2, '.', ',');
                                
                                if($qc_deviation <= 5){
                                    $qc_score_score = 15;
                                    if($qc_deviation == 0) {
                                        $qc_bonus_bonus = 5;
                                    }else {
                                        $qc_bonus_bonus = 0;
                                    }
                                }
                                else{
                                    $qc_score_score = 0;
                                }
                            }
    
                            $rebuttal_accept = $row->rebuttal_accept;
                            $rebuttal_accept_percent = 0;
                            $rebuttal_score = 0;
                            $rebuttal_bonus = 0;
    
                            if($row->audits != 0){
                                $rebuttal_accept_percent = number_format(($row->rebuttal_accept/ $row->audits) * 100, 2, '.', ',');
                                
                                if($rebuttal_accept_percent <= 2){
                                    $rebuttal_score = 20;
                                    if($rebuttal_accept_percent == 0) {
                                        $rebuttal_bonus = 5;
                                    }else {
                                        $rebuttal_bonus = 0;
                                    }
                                }
                                else{
                                    $rebuttal_score = 0;
                                }
                            }
    
                            $training_pkt = $row->training_pkt;
                            $training_pkt_score = 0;
                            $training_pkt_bonus = 0;
    
                            
                            if($training_pkt > 85){
                                $training_pkt_score = 10;
                                if($training_pkt == 100) {
                                    $training_pkt_bonus = 5;
                                }else {
                                    $training_pkt_bonus = 0;
                                }
                            }
                            else{
                                $training_pkt_score = 0;
                            }
                            
                            $calibration = 0;
                            $calibration_score = 0;
                            $calibration_bonus = 0;
                            $total_score = $audit_score + $qc_score_score + $rebuttal_score + $training_pkt_score + $calibration_score;
                            $total_bonus = $audit_bonus + $qc_bonus_bonus + $rebuttal_bonus + $training_pkt_bonus + $calibration_bonus;
                            $total_score_with_bonus = $audit_score + $audit_bonus + $qc_score_score + $qc_bonus_bonus + $rebuttal_score + $rebuttal_bonus + $training_pkt_score + $training_pkt_bonus + $calibration_score + $calibration_bonus;
                        ?>
                        <td>
                            {{$audit_score}}
                        </td>
                        <td>
                            {{ $qc_score_score }}
                        </td>
                        <td>
                            {{$rebuttal_score}}
                        </td>
                        <td>
                            {{ $training_pkt_score }}
                        </td>
                        <td>
                            {{ $calibration_score }}
                        </td>
                        <td>
                            {{ $total_score }}
                        </td >
                        <td class = "hide_1"> 
                            {{$total_bonus}}
                        </td>
                        <td>
                            {{$total_score_with_bonus}}
                        </td>
                        <td>
                            @php($j = 1)
                            @foreach($rank as $key=>$value)
                                @if($key == $row->id) 
                                    {{ $j }}
                                @endif
                                @php($j++)
                            @endforeach
                        </td>
                        
                        <td>
                            {{$audit_target}}
                        </td>
                        <td>
                            {{$audit_achievement}}
                        </td>
                        <td>
                            {{$audit_achievement_percent}}
                        </td>
                        
                        <td>
                            {{$audit_bonus}}
                        </td>
                        <td>
                            {{ $qc_deviation }}
                        </td>
                        
                        <td>
                            {{ $qc_bonus_bonus }}
                        </td>
                        
                        <td class = "hide_1">
                            {{$rebuttal_accept}}
                        </td>
                        <td>
                            {{$rebuttal_accept_percent}}
                        </td>
                        
                        <td>
                            {{$rebuttal_bonus}}
                        </td>
                        <td>
                            {{ $training_pkt }}
                        </td>
                        
                        <td>
                            {{ $training_pkt_bonus }}
                        </td>
    
                        <td>
                            {{ $calibration }}
                        </td>
                        
                        <td>
                            {{ $calibration_bonus }}
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