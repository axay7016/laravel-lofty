@extends('master')
@section('main-section')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Cadence Report</h4>
        </div>
    </div>
</div>
<style>
/* .col-sm-12 {
    position: relative;
    overflow: auto;
    width: 100%;
} */
</style>
<!-- end page title -->
<script>
$(document).ready( function() {
//     $('#scroll-horizontal-datatable').dataTable({
//         // order:[]
//         "columnDefs": [
//     { "orderable": false, "targets": 0 }
//   ]
//     });

})
</script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="basic-datatable-preview">
                        <table id="scroll-horizontal-datatable" class="table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>TEAM TOTALS</th>
                                    <th>Reps</th>
                                    <th>Days</th>
                                    <th>Dails</th>
                                    <th>Dails/day Team</th>
                                    <th>Dails/day Rep</th>
                                    <th>Talk Time</th>
                                    <th>TT/day</th>
                                    <th>Decision Maker</th>
                                    <th>Influencer</th>
                                    <th>Connections</th>
                                    <th>Connection rate</th>
                                    <th>Hook Reject</th>
                                    <th>Hook Accepted</th>
                                    <th>%Hook accepted</th>
                                    <th>Pitch Reject</th>
                                    <th>Qualified Pitch</th>
                                    <th>%QP</th>
                                    <th>%QP to close</th>
                                    <th>% Connection to Close</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $cRate_total = 0;
                                $hookReject_total = 0;
                                $qp_total = 0;
                                $qpClose_total = 0;
                                $connectionClose_total = 0;
                                $ttDay_total = 0;
                                $dailsDay_total = 0;
                                $qualifiedPitch_total = 0;
                                @endphp
                                <!-- Executive report data -->
                                @foreach($e_data as $datas)
                                @php
                                $days = isset($datas->days) ? $datas->days : 0;
                                $talktime = isset($datas->talktime) ? $datas->talktime : 0;
                                $hookReject = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                                $hookAccepted = isset($datas->sentiment['Hook Accepted']) ? $datas->sentiment['Hook Accepted'] : 0;
                                $decisionMaker = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                                $influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                                $dails = isset($datas->dails) ? $datas->dails : 0;
                                $demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                                $qualified_count = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                                $pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                                @endphp
                                <tr>
                                    <td>{{isset($datas->team_total) ? $datas->team_total : null}}</td>
                                    <td></td>
                                    <td>{{$days}}</td>
                                    <td>{{$dails}}</td>
                                    <td>
                                        <!-- Dails/day team -->
                                        @php
                                        if($dails == 0 || $days == 0){
                                        echo 0;
                                        }else{
                                        $dails_day = round($dails / $days);
                                        echo $dails_day;
                                        $dailsDay_total = $dailsDay_total + $dails_day;
                                        }
                                        @endphp
                                    </td>
                                    <td></td>
                                    <td>{{$talktime}}</td>
                                    <td>
                                        <!-- TT/day -->
                                        @php
                                        if($days == 0 || $talktime == 0){
                                        echo 0;
                                        }else{
                                        $tt_day = Round($talktime / $days);
                                        $ttDay_total = $ttDay_total + $tt_day;
                                        echo $tt_day;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$decisionMaker}}</td>
                                    <td>{{$influencer}}</td>
                                    <td>
                                        <!-- Connections -->
                                        @php
                                        $connection = $decisionMaker + $influencer;
                                        echo $connection;
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- Connection Rate -->
                                        @php
                                        if($dails == 0 || $connection == 0){
                                        echo 0;
                                        }else{
                                        $connection_rate = ROUND($connection/$dails,2);
                                        $cRate_total = $cRate_total + $connection_rate;
                                        echo $connection_rate;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$hookReject}}</td>
                                    <td>{{$hookAccepted}}</td>
                                    <td>
                                        <!-- % Hook Accepted -->
                                        @php
                                        $hookAccepted_per = Round($executive_count['connection_total'] - $hookReject,2);
                                        $hookReject_total = $hookReject_total + $hookAccepted_per;
                                        echo $hookAccepted_per;
                                        @endphp
                                    </td>
                                    <td>{{$pitchRejected}}</td>
                                    <td>
                                        <!-- Qualified Pitch -->
                                        @php
                                        $qualifiedPitch = $qualified_count + $demo;
                                        $qualifiedPitch_total = $qualifiedPitch_total + $qualifiedPitch;
                                        echo $qualifiedPitch;
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- Qp -->
                                        @php
                                        if($qualifiedPitch == 0 || $hookAccepted == 0){
                                        echo 0;
                                        }else{
                                        $qp = ROUND($qualifiedPitch / $hookAccepted,2);
                                        $qp_total = $qp_total + $qp;
                                        echo $qp;
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Qp to close -->
                                        @php
                                        if($qualifiedPitch == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $qpClose = ROUND(($demo / $qualifiedPitch) * 100,2);
                                        $qpClose_total = $qpClose_total + $qpClose;
                                        echo $qpClose."%";
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Connection to close -->
                                        @php
                                        if($connection == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $connection_close = ROUND(($demo / $connection) * 100,2);
                                        $connectionClose_total = $connectionClose_total + $connection_close;
                                        echo $connection_close."%";
                                        }
                                        @endphp
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <!--executive report total count in some calculation is this blade file and some calculation available in controller-->
                                    <th>Total</th>
                                    <td></td>
                                    <td>{{$executive_count['day_total']}}</td>
                                    <td>{{$executive_count['dails_total']}}</td>
                                    <td>{{$dailsDay_total}}</td>
                                    <td></td>
                                    <td>{{$executive_count['talktime_total']}}</td>
                                    <td>{{$ttDay_total}}</td>
                                    <td>{{$executive_count['decision_total']}}</td>
                                    <td>{{$executive_count['influencer_total']}}</td>
                                    <td>{{$executive_count['connection_total']}}</td>
                                    <td>{{$cRate_total}}</td>
                                    <td>{{$executive_count['hookRejected_total']}}</td>
                                    <td>{{$executive_count['hookAccepted_total']}}</td>
                                    <td>{{$hookReject_total}}</td>
                                    <td>{{$executive_count['pitchRejected_total']}}</td>
                                    <td>{{$qualifiedPitch_total}}</td>
                                    <td>{{$qp_total}}</td>
                                    <td>{{$qpClose_total}}%</td>
                                    <td>{{$connectionClose_total}}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- end preview-->

                </div> <!-- end tab-content-->

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
@endsection