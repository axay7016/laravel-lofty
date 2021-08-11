<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Slcallinfo extends Model
{
    use HasFactory;
    protected $table = 'sl_call_informations';

    //get perticuler user call record
    public function getSlUserCall($firstdate,$lastdate){
        $slcalluser = Slcallinfo::whereNull('call_type')
                    ->where('salesloft_user_id',23669)
                    ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$firstdate)
                    ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$lastdate)
                    ->count();
                    //'%Y-%m-%dT%H:%i:%s'
        return $slcalluser;
    }

    //get perticluer user calldata record
    public function getSlUserCalldata($firstdate,$lastdate){
        $slusercalldata = Slcallinfo::whereNotNull('call_type')
                    ->where('salesloft_user_id',23669)
                    ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$firstdate)
                    ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$lastdate)
                    ->where('direction','outbound')
                    ->count();
                    //'%Y-%m-%dT%H:%i:%s'
        return $slusercalldata;
    }

    //Data Filter 
    public function getdate(){
        $data = Slcallinfo::selectRaw("Min(salesloft_created_at) as minDate2,Max(salesloft_created_at) as maxDate2,Min(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as minDate,
        Max(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as maxDate")->get();
        return $data;
    }
    //cadencereport in get name 
    public function getSlCadenceName($f_date , $l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $dails = Slcallinfo::selectRaw('cadence_id,(select name from sl_cadence where sl_cadence.sl_cadence_id = sl_call_informations.cadence_id limit 1) as name,count(*) as dails')
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNotNull('call_id')
        ->whereNotNull('cadence_id')
        ->groupBy('cadence_id')
        ->orderBy('cadence_id')
        ->get();
        return $dails;
    }

    //cadencereport in get disposition count
    public function getSlDisposition($f_date , $l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $cadence_disposition = Slcallinfo::selectRaw('cadence_id,disposition,count(disposition) as disposition_count')
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNotNull('cadence_id')
        ->whereNotNull('call_id')
        ->orderBy('cadence_id')
        ->groupBy('cadence_id','disposition')->get();
        return $cadence_disposition;
    }

    //cadencereport in get sentiment count
    public function getSlSentiment($f_date  , $l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $cadence_sentiment = Slcallinfo::selectRaw('cadence_id,sentiment,count(sentiment) as sentiment_count')
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNotNull('cadence_id')
        ->whereNotNull('call_id')
        ->orderBy('cadence_id')
        ->groupBy('cadence_id','sentiment')->get();
        return $cadence_sentiment;
    }

    //cadencereport in get dails
    public function getSlDails($cadenceId,$f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $datas = Slcallinfo::selectRaw('count(call_id) as total')
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereIn('call_id',[DB::raw('select call_id from sl_call_informations where '.$cadenceId.' = cadence_id')])
        ->whereNotNull('call_type')
        ->get();
        return $datas;
    }

     //executivreport in get date,dails,day and talktime
     public function getSlExecutiveTeam($f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }elseif($f_date == $l_date || $f_date != $l_date){
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }else{
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }
        $team_total = Slcallinfo::selectRaw("count(call_id) as dails,".$query.",count(DISTINCT STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as days,Round(sum(duration)) as talktime")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNotNull('call_type')
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total')
        ->get();
        return $team_total;
    }

    //executivreport in get disposition count
    public function getSlExecutiveDisposition($f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }elseif($f_date == $l_date || $f_date != $l_date){
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }else{
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }
        $executiv_disposition = Slcallinfo::selectRaw("disposition,count(disposition) as disposition_count,".$query."")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total','disposition')
        ->get();
        return $executiv_disposition;
    }

    //executivreport in get sentiment count
    public function getSlExecutiveSentiment($f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }elseif($f_date == $l_date || $f_date != $l_date){
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }else{
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        }
        $executiv_sentiment = Slcallinfo::selectRaw("sentiment,count(sentiment) as sentiment_count,".$query."")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total','sentiment')
        ->get();
        return $executiv_sentiment;
    }

    //get single repsId
    public function getSingleRepId($f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $peopleId = Slcallinfo::select('salesloft_user_id')
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNull('call_type')
        ->groupBy('salesloft_user_id')
        ->get();
        return $peopleId;
    }



    //get single rep in get rep name,dails,day and talktime
    public function getSlSingleRepName($id,$f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $rep_name = Slcallinfo::selectRaw("salesloft_user_id,count(call_id) as dails,count(DISTINCT STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as days,Round(sum(duration)) as talktime")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->where('salesloft_user_id','=',$id)
        ->whereNotNull('call_type')
        // ->orderBy('salesloft_created_at','asc')
        ->groupBy('salesloft_user_id')
        ->get();
        return $rep_name;
    }

    //Single Rep report in get disposition count
    public function getSlSingleRepDisposition($f_date,$l_date){  
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }  
        $singlerep_disposition = Slcallinfo::selectRaw("salesloft_user_id,disposition,count(disposition) as disposition_count")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNull('call_type')
        ->groupBy('salesloft_user_id','disposition')
        ->get();
        return $singlerep_disposition;
    }

    //Single Rep report in get sentiment count
    public function getSlSingleRepSentiment($f_date,$l_date){
        $data = Slcallinfo::getdate();
        if($f_date == "" && $l_date == ""){
            $f_date = $data[0]['minDate'];
            $l_date = $data[0]['maxDate'];
        }
        $singlerep_sentiment = Slcallinfo::selectRaw("salesloft_user_id,sentiment,count(sentiment) as sentiment_count")
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'>=',$f_date)
        ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"),'<=',$l_date)
        ->whereNull('call_type')
        ->groupBy('salesloft_user_id','sentiment')
        ->get();
        return $singlerep_sentiment;
    }
}
