                        
                                 @php
                                $c_dial_total = 0;
                                $c_total_leads = 0;
                                $c_conRate_total = 0;
                                $c_hRate_total = 0;
                                $c_qp_total = 0;
                                $c_dailDemo_total = 0;
                                $c_qpClose_total = 0;
                                $c_connectClose_total = 0;
                                $hookAccepted_total = 0;
                                @endphp
                                <!-- cadence report data -->
                                @foreach($c_data as $datas)
                                @php
                                $c_dails = isset($datas->total['total']) ? $datas->total['total'] : 0;
                                $c_decision = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                                $c_influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                                $c_hookRejected = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                                $c_pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                                $c_qualified_Pitch = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                                $c_demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                                $c_qualifiedPitch = $c_qualified_Pitch + $c_demo;
                                @endphp
                                <tr>
                                    <td>{{$datas->name}}</td>
                                    <td>
                                        <!-- helper function (get data using peopleApi)-->
                                        @php
                                        $cadenceId = $datas->cadence_id;
                                        $workinglead = getSalesLoftPeople($cadenceId,$alldate['f_date'],$alldate['l_date']);
                                        $workingLoadInfo = json_decode($workinglead);
                                        $leads_count = $workingLoadInfo->metadata->paging->total_count;
                                        $c_total_leads = $c_total_leads + $leads_count;
                                        echo $leads_count;
                                        @endphp
                                    </td>
                                    <td>@php
                                     echo $c_dails;
                                     $c_dial_total = $c_dial_total + $c_dails;
                                     @endphp
                                     </td>
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
                                        $c_connectionRate = number_format($c_connection / $c_dails,2);
                                        $c_conRate_total = $c_conRate_total + $c_connectionRate;
                                        echo $c_connectionRate;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$c_hookRejected}}</td>
                                    <td>
                                    <!-- Hook Accepted -->
                                    @php
                                    $c_hookAccepted = $c_pitchRejected + $c_qualifiedPitch;
                                    $hookAccepted_total = $hookAccepted_total + $c_hookAccepted;
                                    echo $c_hookAccepted;
                                    @endphp
                                    </td>
                                    <td>
                                        <!-- % Hook Accepted -->
                                        @php
                                        if($c_hookAccepted == 0 || $c_connection == 0){
                                            echo 0;
                                        }
                                        else{
                                        $c_hookAccepted_per = number_format(($c_hookAccepted / $c_connection) * 100,2);
                                        $c_hRate_total = $c_hRate_total + $c_hookAccepted_per;
                                        echo $c_hookAccepted_per.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$c_pitchRejected}}</td>
                                    <td>{{$c_qualifiedPitch}}</td>
                                    <td>
                                        <!-- % Qp -->
                                        @php
                                        if($c_qualifiedPitch == 0 || $c_hookAccepted == 0){
                                        echo 0;
                                        }else{
                                        $qp = number_format(($c_qualifiedPitch / $c_hookAccepted)*100,2);
                                        $c_qp_total = $c_qp_total+$qp;
                                        echo $qp.'%';
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
                                        $c_demo_dails = number_format($c_dails / $c_demo,2);
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
                                        $c_qp_close = number_format(($c_demo / $c_qualifiedPitch)*100,2);
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
                                        $c_connection_close = number_format(($c_demo / $c_connection) * 100,2);
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
                                    <td>{{$c_dial_total}}</td>
                                    <td>{{$total_count['decision_total']}}</td>
                                    <td>{{$total_count['influencer_total']}}</td>
                                    <td>{{$total_count['connection_total']}}</td>
                                    <td>{{$c_conRate_total}}</td>
                                    <td>{{$total_count['hookRejected_total']}}</td>
                                    <td>{{$hookAccepted_total}}</td>
                                    <td>{{number_format($c_hRate_total,2)}}%</td>
                                    <td>{{$total_count['pitchRejected_total']}}</td>
                                    <td>{{$total_count['qualified_total']}}</td>
                                    <td>{{$c_qp_total}}%</td>
                                    <td>{{$total_count['demo_total']}}</td>
                                    <td>{{$c_dailDemo_total}}</td>
                                    <td>{{number_format($c_qpClose_total,2)}}%</td>
                                    <td>{{number_format($c_connectClose_total,2)}}%</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>

                            
                        