<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\salesloft\Slcallinfo;

class SalesloftReport extends Controller
{
    //Cadence Report 
    public function slCadenceReport(){
        $call_information = new Slcallinfo();
        $defaul_date = $call_information->getdate();
            $f_date =  $defaul_date[0]['minDate2'];
            $l_date =  $defaul_date[0]['maxDate2'];  

        //get cadence name, dispositon count, sentiment count
        $dails = $call_information->getSlCadenceName($f_date , $l_date);
        $disposition = $call_information->getSlDisposition($f_date , $l_date);
        $sentiment = $call_information->getSlSentiment($f_date , $l_date);

        foreach ($dails as $key => $value) {
            $d_count = array();
            $dis = array();
            $set = array();

            //get dials using cadence id
            $dails_count = $call_information->getSlDails($value->cadence_id,$f_date,$l_date);

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

        $alldate = [
            "f_date"=>$f_date,
            "l_date"=>$l_date
        ];
        //total count of cadence report
        $c_total_count = SalesloftReport::reportTotal($dails);

        return view('report.cadencereport',['c_data'=>$dails,'total_count'=>$c_total_count,'alldate'=>$alldate]);
       
    }

    //Cadence Report Filter
    function cadenceFilter(Request $request){
        $f_date =  $request->starting_date;
        $l_date =  $request->ending_date; 

        $call_information = new Slcallinfo();
        //get cadence name, dispositon count, sentiment count
        $dails = $call_information->getSlCadenceName($f_date , $l_date);
        $disposition = $call_information->getSlDisposition($f_date , $l_date);
        $sentiment = $call_information->getSlSentiment($f_date , $l_date);

        foreach ($dails as $key => $value) {
            $d_count = array();
            $dis = array();
            $set = array();

            //get dials using cadence id
            $dails_count = $call_information->getSlDails($value->cadence_id,$f_date,$l_date);

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

        $alldate = [
            "f_date"=>$f_date,
            "l_date"=>$l_date
        ];
        //total count of cadence report
        $c_total_count = SalesloftReport::reportTotal($dails);

        return view('report.cadencefilter',['c_data'=>$dails,'total_count'=>$c_total_count,'alldate'=>$alldate])->render();
    }
    
    // Executive Report
    function slExecutiveReport(){
        $call_information = new Slcallinfo();
        $f_date = "";
        $l_date = "";
        
        //get date,dails,day and talktime, disposition count, sentiment count
        $team_total = $call_information->getSlExecutiveTeam($f_date,$l_date);
        $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date,$l_date);
        $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date,$l_date);

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

    // Executive Report Filter
    function executiveFilter(Request $request){
        $call_information = new Slcallinfo();
        $f_date =  isset($request->starting_date) ? $request->starting_date : "";
        $l_date =  isset($request->ending_date) ? $request->ending_date : "";

        //get date,dails,day and talktime, disposition count, sentiment count
        $team_total = $call_information->getSlExecutiveTeam($f_date,$l_date);
        $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date,$l_date);
        $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date,$l_date);

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
        
        return view('report.executivfilter',['e_data'=>$team_total,'executive_count'=>$total_count])->render();
    }

    // Single Reps Report 
    function slSingleRepReport(){
        $call_information = new Slcallinfo();     
        $f_date = "";
        $l_date = "";
        
        //get reps id, dails, days, talktime, dispositon count, sentiment count
        $repId = $call_information->getSingleRepId($f_date,$l_date);
        $disposition = $call_information->getSlSingleRepDisposition($f_date,$l_date);
        $sentiment = $call_information->getSlSingleRepSentiment($f_date,$l_date);

        foreach ($repId as $key => $value) {
            $repdetails = array();
            $dis = array();
            $set = array();
            //get reps name, dails, days, talktime
            $repname = $call_information->getSlSingleRepName($value->salesloft_user_id,$f_date,$l_date);

            
            foreach($repname as $val){
                    $repdetails['dails'] = $val->dails;
                    $repdetails['days'] = $val->days;
                    $repdetails['talktime'] = $val->talktime;
                    
            }
            //set reps name, dails, days, talktime
            $repId[$key]->repname = $repdetails;
            
            foreach ($disposition as $dispositionRow) {
                if ($value->salesloft_user_id === $dispositionRow->salesloft_user_id) {
                    $dis[$dispositionRow->disposition] = $dispositionRow->disposition_count;
                }
            }
            //set disposition count
            $repId[$key]->disposition = $dis;

            foreach ($sentiment as $sentimentRow) {
                if ($value->salesloft_user_id === $sentimentRow->salesloft_user_id) {
                    $set[$sentimentRow->sentiment] = $sentimentRow->sentiment_count;
                }
            }
            //set  sentiment count
            $repId[$key]->sentiment = $set;

        }

        //total count of single reps report
        $r_total_count = SalesloftReport::reportTotal($repId);

        return view('report.singlerep',['r_data'=>$repId,'r_count'=>$r_total_count]);
    }

    // Single Reps Report Filter
    function singlerepFilter(Request $request){
        $call_information = new Slcallinfo();  
        $f_date =  isset($request->starting_date) ? $request->starting_date : "";
        $l_date =  isset($request->ending_date) ? $request->ending_date : "";

        //get reps id, dails, days, talktime, dispositon count, sentiment count
        $repId = $call_information->getSingleRepId($f_date,$l_date);
        $disposition = $call_information->getSlSingleRepDisposition($f_date,$l_date);
        $sentiment = $call_information->getSlSingleRepSentiment($f_date,$l_date);

        foreach ($repId as $key => $value) {
            $repdetails = array();
            $dis = array();
            $set = array();

            //get reps name, dails, days, talktime
            $repname = $call_information->getSlSingleRepName($value->salesloft_user_id,$f_date,$l_date);

            foreach($repname as $val){
                    $repdetails['dails'] = $val->dails;
                    $repdetails['days'] = $val->days;
                    $repdetails['talktime'] = $val->talktime;
                    
            }
            //set reps name, dails, days, talktime
            $repId[$key]->repname = $repdetails;

            foreach ($disposition as $dispositionRow) {
                if ($value->salesloft_user_id === $dispositionRow->salesloft_user_id) {
                    $dis[$dispositionRow->disposition] = $dispositionRow->disposition_count;
                }
            }
            //set disposition count
            $repId[$key]->disposition = $dis;

            foreach ($sentiment as $sentimentRow) {
                if ($value->salesloft_user_id === $sentimentRow->salesloft_user_id) {
                    $set[$sentimentRow->sentiment] = $sentimentRow->sentiment_count;
                }
            }
            //set sentiment count
            $repId[$key]->sentiment = $set;
        }

        //total count of single reps report
        $r_total_count = SalesloftReport::reportTotal($repId);

        return view('report.singlerepfilter',['r_data'=>$repId,'r_count'=>$r_total_count])->render();

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
            $dails = isset($data->dails) ? $data->dails : 0;
            $talktime = isset($data->talktime) ? $data->talktime : 0;
            $decisionMaker = isset($data->disposition['Decision Maker']) ? $data->disposition['Decision Maker'] : 0;
            $influencer = isset($data->disposition['Influencer']) ? $data->disposition['Influencer'] : 0;
            $hookReject = isset($data->sentiment['Hook Rejected']) ? $data->sentiment['Hook Rejected'] : 0;
            $hookAccepted = isset($data->sentiment['Hook Accepted']) ? $data->sentiment['Hook Accepted'] : 0;
            $pitchRejected = isset($data->sentiment['Pitch Rejected']) ? $data->sentiment['Pitch Rejected'] : 0;
            $qualified_Pitch = isset($data->sentiment['Qualified Pitch']) ? $data->sentiment['Qualified Pitch'] : 0;
            $demo = isset($data->sentiment['Demo Scheduled']) ? $data->sentiment['Demo Scheduled'] : 0; 
            
            $day_total = $day_total + $days;
            $dails_total = $dails_total + $dails;
            $talktime_total = $talktime_total + $talktime;
            $decision_total = $decision_total + $decisionMaker;
            $influencer_total = $influencer_total + $influencer;
            $count = $decisionMaker + $influencer;
            $connection_total = $connection_total + $count;
            $hookRejected_total = $hookRejected_total + $hookReject;
            $hookAccepted_total = $hookAccepted_total + $hookAccepted;
            $pitchRejected_total = $pitchRejected_total + $pitchRejected;
            $qualifiedPitch_total = $qualifiedPitch_total + $qualified_Pitch;
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
