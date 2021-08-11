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

    //cadencereport in get name 
    public function getSlCadenceName(){
        $dails = Slcallinfo::selectRaw('cadence_id,(select name from sl_cadence where sl_cadence.sl_cadence_id = sl_call_informations.cadence_id limit 1) as name,count(*) as dails')
        ->whereNotNull('call_id')
        ->whereNotNull('cadence_id')
        ->groupBy('cadence_id')
        ->orderBy('cadence_id')
        ->get();
        return $dails;
    }

    //cadencereport in get disposition count
    public function getSlDisposition(){
        $cadence_disposition = Slcallinfo::selectRaw('cadence_id,disposition,count(disposition) as disposition_count')
        ->whereNotNull('cadence_id')
        ->whereNotNull('call_id')
        ->orderBy('cadence_id')
        ->groupBy('cadence_id','disposition')->get();
        return $cadence_disposition;
    }

    //cadencereport in get sentiment count
    public function getSlSentiment(){
        $cadence_sentiment = Slcallinfo::selectRaw('cadence_id,sentiment,count(sentiment) as sentiment_count')
        ->whereNotNull('cadence_id')
        ->whereNotNull('call_id')
        ->orderBy('cadence_id')
        ->groupBy('cadence_id','sentiment')->get();
        return $cadence_sentiment;
    }

    //cadencereport in get dails
    public function getSlDails($cadenceId){
        $data = Slcallinfo::selectRaw('count(call_id) as total')
        ->whereIn('call_id',[DB::raw('select call_id from sl_call_informations where '.$cadenceId.' = cadence_id')])
        ->whereNotNull('call_type')
        ->get();
        return $data;
    }

     //executivreport in get date,dails,day and talktime
     public function getSlExecutiveTeam(){
        $team_total = Slcallinfo::selectRaw("count(call_id) as dails,DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total,count(DISTINCT STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as days,Round(sum(duration)) as talktime")
        ->whereNotNull('call_type')
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total')
        ->get();
        return $team_total;
    }

    //executivreport in get disposition count
    public function getSlExecutiveDisposition(){
        $executiv_disposition = Slcallinfo::selectRaw("disposition,count(disposition) as disposition_count,DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total")
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total','disposition')
        ->get();
        return $executiv_disposition;
    }

    //executivreport in get sentiment count
    public function getSlExecutiveSentiment(){
        $executiv_sentiment = Slcallinfo::selectRaw("sentiment,count(sentiment) as sentiment_count,
        DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total")
        ->orderBy('salesloft_created_at','asc')
        ->groupBy('team_total','sentiment')
        ->get();
        return $executiv_sentiment;
    }

}
