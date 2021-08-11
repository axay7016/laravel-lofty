
                                @php
                                $cRate_total = 0;
                                $hookacceptRate_total = 0;
                                $qp_total = 0;
                                $qpClose_total = 0;
                                $connectionClose_total = 0;
                                $ttDay_total = 0;
                                $dailsDay_total = 0;
                                $qualifiedPitch_total = 0;
                                $hookAccepted_total = 0;
                                @endphp
                                <!-- Executive report data -->
                                @foreach($e_data as $datas)
                                @php
                                $days = isset($datas->days) ? $datas->days : 0;
                                $talktime = isset($datas->talktime) ? $datas->talktime : 0;
                                $hookReject = isset($datas->sentiment['Hook Rejected']) ? $datas->sentiment['Hook Rejected'] : 0;
                                $decisionMaker = isset($datas->disposition['Decision Maker']) ? $datas->disposition['Decision Maker'] : 0;
                                $influencer = isset($datas->disposition['Influencer']) ? $datas->disposition['Influencer'] : 0;
                                $dails = isset($datas->dails) ? $datas->dails : 0;
                                $demo = isset($datas->sentiment['Demo Scheduled']) ? $datas->sentiment['Demo Scheduled'] : 0;
                                $qualified_count = isset($datas->sentiment['Qualified Pitch']) ? $datas->sentiment['Qualified Pitch'] : 0;
                                $pitchRejected = isset($datas->sentiment['Pitch Rejected']) ? $datas->sentiment['Pitch Rejected'] : 0;
                                $qualifiedPitch = $qualified_count + $demo;
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
                                        $connection_rate = number_format($connection/$dails,2);
                                        $cRate_total = $cRate_total + $connection_rate;
                                        echo $connection_rate;
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$hookReject}}</td>
                                    <td>
                                    @php
                                    $hookAccepted = $pitchRejected + $qualifiedPitch;
                                    $hookAccepted_total = $hookAccepted_total + $hookAccepted;
                                    @endphp
                                    {{$hookAccepted}}
                                    </td>
                                    <td>
                                        <!-- % Hook Accepted -->
                                        @php
                                        if($hookAccepted == 0 || $connection == 0){
                                            echo 0;
                                        }else{
                                        $hookAccepted_per = number_format(($hookAccepted/$connection) * 100,2);
                                        $hookacceptRate_total = $hookacceptRate_total + $hookAccepted_per;
                                        echo $hookAccepted_per.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>{{$pitchRejected}}</td>
                                    <td>
                                        <!-- Qualified Pitch -->
                                        @php
                                        $qualifiedPitch_total = $qualifiedPitch_total + $qualifiedPitch;
                                        echo $qualifiedPitch;
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- %Qp -->
                                        @php
                                        if($qualifiedPitch == 0 || $hookAccepted == 0){
                                        echo 0;
                                        }else{
                                        $qp = number_format(($qualifiedPitch / $hookAccepted) * 100,2);
                                        $qp_total = $qp_total + $qp;
                                        echo $qp.'%';
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        <!-- % Qp to close -->
                                        @php
                                        if($qualifiedPitch == 0 || $demo == 0){
                                        echo 0;
                                        }else{
                                        $qpClose = number_format(($demo / $qualifiedPitch) * 100,2);
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
                                        $connection_close = number_format(($demo / $connection) * 100,2);
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
                                    <td>{{$hookAccepted_total}}</td>
                                    <td>{{number_format($hookacceptRate_total,2)}}%</td>
                                    <td>{{$executive_count['pitchRejected_total']}}</td>
                                    <td>{{$qualifiedPitch_total}}</td>
                                    <td>{{number_format($qp_total,2)}}%</td>
                                    <td>{{number_format($qpClose_total,2)}}%</td>
                                    <td>{{number_format($connectionClose_total,2)}}%</td>
                                </tr>
                           