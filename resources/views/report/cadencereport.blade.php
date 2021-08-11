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
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="basic-datatable-preview">
                        <table id="scroll-horizontal-datatable" class="table w-100 nowrap">
                            <thead>
                                <tr>
                                    <th>MTD Cadence Metrics</th>
                                    <th># Working Leads</th>
                                    <th>Dials</th>
                                    <th>Decision Maker</th>
                                    <th>Influencer</th>
                                    <th>Connections</th>
                                    <th>Connection rate</th>
                                    <th>Hook Reject</th>
                                    <th>Hook Accepted</th>
                                    <th>% Hook accepted</th>
                                    <th>Pitch Reject</th>
                                    <th>Qualified Pitch</th>
                                    <th>%QP</th>
                                    <th>Demos*</th>
                                    <th>Dial/Demo</th>
                                    <th>%QP to close</th>
                                    <th>% Connection to Close</th>
                                    <th>Demos Performed</th>
                                    <th>Sales</th>
                                    <th>Booked/Closed %</th>
                                    <th>Given/Closed %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $c_total_leads = 0;
                                $c_conRate_total = 0;
                                $c_hRate_total = 0;
                                $c_qp_total = 0;
                                $c_dailDemo_total = 0;
                                $c_qpClose_total = 0;
                                $c_connectClose_total = 0;
                                @endphp
                                <!-- cadence report data -->
                                @foreach($c_data as $datas)
                                @php
                                $c_dails = isset($datas->total['total']) ? $datas->total['total'] : 0;
                                $c_decision = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                                $c_influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                                $c_hookRejected = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                                $c_hookAccepted = isset($datas->sentiment['Hook Accepted']) ? $datas->sentiment['Hook Accepted'] : 0;
                                $c_pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                                $c_qualified_Pitch = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                                $c_demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                                @endphp
                                <tr>
                                    <td>{{$datas->name}}</td>
                                    <td>
                                        <!-- helper function (get data using peopleApi)-->
                                        @php
                                        $cadenceId = $datas->cadence_id;
                                        $workinglead = getSalesLoftPeople($cadenceId);
                                        $workingLoadInfo = json_decode($workinglead);
                                        $leads_count = $workingLoadInfo->metadata->paging->total_count;
                                        $c_total_leads = $c_total_leads + $leads_count;
                                        echo $leads_count;
                                        @endphp
                                    </td>
                                    <td>{{$c_dails}}</td>
                                    <td>{{$c_decision}}</td>
                                    <td>{{$c_influencer}}</td>
                                    <td>
                                        <!-- Connection -->
                                        @php
                                        $c_connection = $c_decision + $c_influencer;
                                        echo $c_connection;
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- Connection rate -->
                                        @php
                                        if($c_connection == 0 || $c_dails == 0){
                                        echo 0;
                                        }
                                        else{
                                        $c_connectionRate = Round($c_connection / $c_dails,2);
                                        $c_conRate_total = $c_conRate_total + $c_connectionRate;
                                        echo $c_connectionRate;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$c_hookRejected}}</td>
                                    <td>{{$c_hookAccepted}}</td>
                                    <td>
                                        <!-- % Hook Accepted -->
                                        @php
                                        $c_hookAccepted_per = Round($total_count['connection_total'] - $c_hookRejected,2);
                                        $c_hRate_total = $c_hRate_total + $c_hookAccepted_per;
                                        echo $c_hookAccepted_per;
                                        @endphp
                                    </td>
                                    <td>{{$c_pitchRejected}}</td>
                                    <td>
                                        <!-- Qualified pitch -->
                                        @php
                                        $c_qualifiedPitch = $c_qualified_Pitch + $c_demo;
                                        echo $c_qualifiedPitch;
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Qp -->
                                        @php
                                        if($c_qualifiedPitch == 0 || $c_hookAccepted == 0){
                                        echo 0;
                                        }else{
                                        $qp = Round($c_qualifiedPitch / $c_hookAccepted,2);
                                        $c_qp_total = $c_qp_total+$qp;
                                        echo $qp;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$c_demo}}</td>
                                    <td>
                                        <!-- dails/demo -->
                                        @php
                                        if($c_dails == 0 || $c_demo == 0){
                                        echo 0;
                                        }else{
                                        $c_demo_dails = Round($c_dails / $c_demo,2);
                                        $c_dailDemo_total = $c_dailDemo_total + $c_demo_dails;
                                        echo $c_demo_dails;
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Qp to close -->
                                        @php
                                        if($c_demo == 0 || $c_qualifiedPitch == 0){
                                        echo 0;
                                        }else{
                                        $c_qp_close = Round(($c_demo / $c_qualifiedPitch) * 100,2);
                                        $c_qpClose_total = $c_qpClose_total + $c_qp_close;
                                        echo $c_qp_close."%";
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Connection to close -->
                                        @php
                                        if($c_demo == 0 || $c_connection == 0){
                                        echo 0;
                                        }else{
                                        $c_connection_close = Round(($c_demo / $c_connection) * 100,2);
                                        $c_connectClose_total = $c_connectClose_total + $c_connection_close;
                                        echo $c_connection_close."%";
                                        }
                                        @endphp
                                    </td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <!--cadence report total data in some calculation is this blade file and some calculation available in controller-->
                                    <th>Total</th>
                                    <td>{{$c_total_leads}}</td>
                                    <td>{{$total_count['dails_total']}}</td>
                                    <td>{{$total_count['decision_total']}}</td>
                                    <td>{{$total_count['influencer_total']}}</td>
                                    <td>{{$total_count['connection_total']}}</td>
                                    <td>{{$c_conRate_total}}</td>
                                    <td>{{$total_count['hookRejected_total']}}</td>
                                    <td>{{$total_count['hookAccepted_total']}}</td>
                                    <td>{{$c_hRate_total}}</td>
                                    <td>{{$total_count['pitchRejected_total']}}</td>
                                    <td>{{$total_count['qualified_total']}}</td>
                                    <td>{{$c_qp_total}}</td>
                                    <td>{{$total_count['demo_total']}}</td>
                                    <td>{{$c_dailDemo_total}}</td>
                                    <td>{{$c_qpClose_total}}%</td>
                                    <td>{{$c_connectClose_total}}%</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
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