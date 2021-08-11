<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\salesloft\Slcallinfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesloftCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftCall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft call record in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //start date(log)
        $date = Carbon::now();
        Log::channel('slcall')->notice('
        ------------------------------------------------------------
        Start Date = '.$date->format('Y-m-d H:i:s.uP').'
        ');
        $total_data = 0;
        $store_log_id = '';
        //get api data and store that data in database
        $page = 1;
        $last_record = Slcallinfo::whereNull('call_type')->latest('salesloft_updated_at')->first();
        if($last_record == ''){
            $type = 'backword';
            $update_date = Carbon::now()->format('Y-m-d H:i:s.uP');
        }else{
            $type = 'forword';
            $update_date =  Carbon::parse($last_record->salesloft_updated_at)->format('Y-m-d H:i:s.uP');
        }
        do{
            if($type == 'backword' && $page == 26){
                break;
            }
            $call =  getSalesLoftCall($update_date, $page++, $type);//helper function
            $data = json_decode($call);
            $total_data = $total_data + count($data->data);
            $total_page = $data->metadata->paging->total_pages;
            $log_id = '';
            foreach($data->data as $datas){
                $log_id .= $datas->id.',';
                $recordings = json_encode($datas->recordings);
                $data = [
                    'call_id'=> isset($datas->id) ? $datas->id : null,
                    'to'=> isset($datas->to) ? $datas->to : null,
                    'duration'=> isset($datas->duration) ? $datas->duration : null,
                    'sentiment'=> isset($datas->sentiment) ? $datas->sentiment : null,
                    'disposition'=> isset($datas->disposition) ? $datas->disposition : null,
                    'salesloft_created_at'=>isset($datas->created_at) ? $datas->created_at : null,
                    'salesloft_updated_at'=>isset($datas->updated_at) ? $datas->updated_at : null,
                    'recordings'=> isset($recordings) ? $recordings : null,
                    'user_href'=> isset($datas->user->_href) ? $datas->user->_href : null,
                    'salesloft_user_id'=> isset($datas->user->id) ? $datas->user->id : null,
                    'action_id'=> isset($datas->action->id) ? $datas->action->id : null,
                    'called_person_href'=> isset($datas->called_person->_href) ? $datas->called_person->_href : null,
                    'called_person_id'=> isset($datas->called_person->id) ? $datas->called_person->id : null,
                    'crm_activity_href'=> isset($datas->crm_activity->_href) ? $datas->crm_activity->_href : null,
                    'crm_activity_id'=> isset($datas->crm_activity->id) ? $datas->crm_activity->id : null,
                    'note_href'=> isset($datas->note->_href) ? $datas->note->_href : null,
                    'cadence_href'=> isset($datas->cadence->_href) ? $datas->cadence->_href : null,
                    'cadence_id'=> isset($datas->cadence->id) ? $datas->cadence->id : null,
                    'step_href'=> isset($datas->step->_href) ? $datas->step->_href : null,
                    'step_id'=> isset($datas->step->id) ? $datas->step->id : null,
                ];
                //insert salesloft call record
                Slcallinfo::insert($data);
            } 
            $store_log_id .= $log_id;
        }while($page <= $total_page);
        //End date(log)
        $endDate = Carbon::now();
        Log::channel('slcall')->notice('
        Total Pull Call = '.$total_data.'
        Call id = '.$store_log_id.'
        End Date = '.$endDate->format('Y-m-d H:i:s.uP').'
        ------------------------------------------------------------
        ');
        return 0;
    }
}
