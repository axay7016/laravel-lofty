<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\salesloft\Slcallinfo;


class SalesloftReport extends Controller
{
    //Cadence Report
    function slCadenceReport(){
        $call_information = new Slcallinfo();
        //get cadence name
        $dails = $call_information->getSlCadenceName();
        //get dispositon count
        $disposition = $call_information->getSlDisposition();
        //get sentiment count
        $sentiment = $call_information->getSlSentiment();
        foreach ($dails as $key => $value) {
            $d_count = array();
            $dis = array();
            $set = array();
            //get dials using cadence id
            $dails_count = $call_information->getSlDails($value->cadence_id);

            // mearge cadence name,dails,disposition and sentiment data
            foreach($dails_count as $val){
                    $d_count['total'] = $val->total;
            }
            //set dails in set total count of calldata
            $dails[$key]->total = $d_count;
            foreach ($disposition as $dispositionRow) {
                if ($value->cadence_id === $dispositionRow->cadence_id) {
                    $dis[$dispositionRow->disposition] = $dispositionRow->disposition_count;
                }
            }
            //set dails in disposition count
            $dails[$key]->disposition = $dis;
            foreach ($sentiment as $sentimentRow) {
                if ($value->cadence_id === $sentimentRow->cadence_id) {
                    $set[$sentimentRow->sentiment] = $sentimentRow->sentiment_count;
                }
            }
            //set dails in sentiment count
            $dails[$key]->sentiment = $set;
        }
        //total count of cadence report
        $c_total_count = SalesloftReport::reportTotal($dails);

        return view('report.cadencereport',['c_data'=>$dails,'total_count'=>$c_total_count]);
    }
    // Executive Report
    function slExecutiveReport(){
        $call_information = new Slcallinfo();
        //get date,dails,day and talktime
        $team_total = $call_information->getSlExecutiveTeam();
        //get disposition count
        $executiv_disposition = $call_information->getSlExecutiveDisposition();
        //get sentiment count
        $executiv_sentiment = $call_information->getSlExecutiveSentiment();
        foreach ($team_total as $key => $value) {
            $dis = array();
            $set = array();
            // mearge date,dails,day,talktime,disposition and sentiment data
            foreach ($executiv_disposition as $dispositionRow) {
                if($value->team_total == $dispositionRow->team_total){
                    $dis[$dispositionRow->disposition] = $dispositionRow->disposition_count;
                }
            }
            //set team_total in dispositon array
            $team_total[$key]->disposition = $dis;
            foreach ($executiv_sentiment as $sentimentRow) {
                if($value->team_total == $sentimentRow->team_total){
                    $set[$sentimentRow->sentiment] = $sentimentRow->sentiment_count;    
                }
            }
            //set team_total in sentiment array
            $team_total[$key]->sentiment = $set;
        }
        //total count of executive report
        $total_count = SalesloftReport::reportTotal($team_total);
        
        return view('report.executiv',['e_data'=>$team_total,'executive_count'=>$total_count]);
    }

    //total count of report data
    function reportTotal($total){
        $dails_total = 0;
        $decision_total = 0;
        $influencer_total = 0;
        $connection_total = 0;
        $day_total = 0;
        $talktime_total = 0;
        $hookRejected_total = 0;
        $hookAccepted_total = 0;
        $pitchRejected_total = 0;
        $qualifiedPitch_total = 0;
        $demo_total = 0;
        foreach($total as $data){
            $days = isset($data->days) ? $data->days : 0;
            $day_total = $day_total + $days;
            $dails = isset($data->dails) ? $data->dails : 0;
            $dails_total = $dails_total + $dails;
            $talktime = isset($data->talktime) ? $data->talktime : 0;
            $talktime_total = $talktime_total + $talktime;
            $decisionMaker = isset($data->disposition['Decision Maker']) ? $data->disposition['Decision Maker'] : 0;
            $decision_total = $decision_total + $decisionMaker;
            $influencer = isset($data->disposition['Influencer']) ? $data->disposition['Influencer'] : 0;
            $influencer_total = $influencer_total + $influencer;
            $count = $decisionMaker + $influencer;
            $connection_total = $connection_total + $count;
            $hookReject = isset($data->sentiment['Hook Rejected']) ? $data->sentiment['Hook Rejected'] : 0;
            $hookRejected_total = $hookRejected_total + $hookReject;
            $hookAccepted = isset($data->sentiment['Hook Accepted']) ? $data->sentiment['Hook Accepted'] : 0;
            $hookAccepted_total = $hookAccepted_total + $hookAccepted;
            $pitchRejected = isset($data->sentiment['Pitch Rejected']) ? $data->sentiment['Pitch Rejected'] : 0;
            $pitchRejected_total = $pitchRejected_total + $pitchRejected;
            $qualified_Pitch = isset($data->sentiment['Qualified Pitch']) ? $data->sentiment['Qualified Pitch'] : 0;
            $qualifiedPitch_total = $qualifiedPitch_total + $qualified_Pitch;
            $demo = isset($data->sentiment['Demo Scheduled']) ? $data->sentiment['Demo Scheduled'] : 0; 
            $demo_total = $demo_total + $demo;
        }
        $total_count = array(
            "dails_total"=> $dails_total,
            "decision_total"=>$decision_total,
            "influencer_total"=>$influencer_total,
            "connection_total" => $connection_total,
            "day_total" => $day_total,
            "talktime_total" => $talktime_total,
            "hookRejected_total" => $hookRejected_total,
            "hookAccepted_total" => $hookAccepted_total,
            "pitchRejected_total" => $pitchRejected_total,
            "qualified_total"=>$qualifiedPitch_total,
            "demo_total"=>$demo_total
        );
        return $total_count;
    }
}
