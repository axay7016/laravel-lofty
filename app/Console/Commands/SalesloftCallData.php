<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\salesloft\Slcallinfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SalesloftCallData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftCallData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft calldata record in database';

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
        Log::channel('slcalldata')->notice('
        ------------------------------------------------------------
        Start Date = '.$date->format('Y-m-d H:i:s.uP').'
        ');
        $total_data = 0;
        $store_log_id = '';
        //get api data and store that data in database
        $page=1;
        $last_record = Slcallinfo::whereNotNull('call_type')->latest('salesloft_updated_at')->first();
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
            $calldata =  getSalesLoftCallData($update_date, $page++, $type);//helper function
            $calldatas = json_decode($calldata);
            $total_data = $total_data + count($calldatas->data);
            $total_page = $calldatas->metadata->paging->total_pages;
            $log_id = '';
            foreach($calldatas->data as $data){
                $log_id .= $data->id.',';
                $recordings = json_encode($data->recording);
                $call_data = [
                        'call_id'=> isset($data->call->id) ? $data->call->id : null,
                        'salesloft_created_at'=>isset($data->created_at) ? $data->created_at : null,
                        'salesloft_updated_at'=>isset($data->updated_at) ? $data->updated_at : null,
                        'to'=>isset($data->to) ? $data->to : null,
                        'duration'=>isset($data->duration) ? $data->duration : null,
                        'recordings'=> isset($recordings) ? $recordings : null,
                        'user_href'=> isset($data->user->_href) ? $data->user->_href : null,
                        'salesloft_user_id'=> isset($data->user->id) ? $data->user->id : null,
                        'called_person_href'=> isset($data->called_person->_href) ? $data->called_person->_href : null,
                        'called_person_id'=> isset($data->called_person->id) ? $data->called_person->id : null,
                        'parent_id'=>isset($data->call->id) ? $data->call->id : 0,
                        'direction'=>isset($data->direction) ? $data->direction : null,
                        'status'=>isset($data->status) ? $data->status : null,
                        'call_type'=>isset($data->call_type) ? $data->call_type : null,
                        'call_uuid'=>isset($data->call_uuid) ? $data->call_uuid : null,
                        'call_href'=>isset($data->called_person->_href) ? $data->called_person->_href : null,
                    ];
                    //insert salesloft calldata record
                    Slcallinfo::insert($call_data);   
                }
                $store_log_id .= $log_id;
        }while($page <= $total_page);
        //End date(log)
        $endDate = Carbon::now();
        Log::channel('slcalldata')->notice('
        Total Pull CallData = '.$total_data.'
        Call id = '.$store_log_id.'
        End Date = '.$endDate->format('Y-m-d H:i:s.uP').'
        ------------------------------------------------------------
        ');
        return 0;
    }
}
