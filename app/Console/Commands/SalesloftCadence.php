<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\salesloft\Slcadence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SalesloftCadence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftCadence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft cadence data in database';

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
        //start Date (log)
        $date = Carbon::now();
        Log::channel('slcadence')->notice('
        ------------------------------------------------------------
        Start Date = '.$date->format('Y-m-d H:i:s.uP').'
        ');
        $total_data = 0;
        $store_log_id = '';
        //get data in api and store that data in database
        $page=1;
        $last_record = Slcadence::select('salesloft_updated_at')->latest('salesloft_updated_at')->first();
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
            $cadence =  getSalesLoftCadence($update_date, $page++, $type);//helper function
            $cadences = json_decode($cadence);
            $total_data = $total_data + count($cadences->data);
            $total_page = $cadences->metadata->paging->total_pages;
            $log_id = '';
            foreach($cadences->data as $data){
                $tags = json_encode($data->tags);
                $log_id .= $data->id.',';
                $cadence_data = [
                        'sl_cadence_id'=> isset($data->id) ? $data->id : null,
                        'salesloft_created_at'=> isset($data->created_at) ? $data->created_at : null,
                        'salesloft_updated_at'=> isset($data->updated_at) ? $data->updated_at : null,
                        'archived_at'=> isset($data->archived_at) ? $data->archived_at : null,
                        'team_cadence'=> isset($data->team_cadence) ? $data->team_cadence : null,
                        'shared'=> isset($data->shared) ? $data->shared : null,
                        'remove_bounces_enabled'=> isset($data->remove_bounces_enabled) ? $data->remove_bounces_enabled : null,
                        'remove_replies_enabled'=> isset($data->remove_replies_enabled) ? $data->remove_replies_enabled : null,
                        'opt_out_link_included'=> isset($data->opt_out_link_included) ? $data->opt_out_link_included : null,
                        'draft' => isset($data->draft) ? $data->draft : null,
                        'cadence_framework_id' => isset($data->cadence_framework_id) ? $data->cadence_framework_id : null,
                        'cadence_function' => isset($data->cadence_function) ? $data->cadence_function : null,
                        'name'=> isset($data->name) ? $data->name : null,
                        'tags'=> isset($tags) ? $tags : null,
                        'creator_id'=> isset($data->creator->id) ? $data->creator->id : null,
                        'creator_href'=> isset($data->creator->_href) ? $data->creator->_href : null,
                        'owner_id'=> isset($data->owner->id) ? $data->owner->id : null,
                        'owner_href'=> isset($data->owner->_href) ? $data->owner->_href : null,
                        'bounced_stage_id'=> isset($data->bounced_stage->id) ? $data->bounced_stage->id : null,
                        'bounced_stage_href'=> isset($data->bounced_stage->_href) ? $data->bounced_stage->_href : null,
                        'replied_stage_id'=> isset($data->replied_stage->id) ? $data->replied_stage->id : null,
                        'replied_stage_href'=> isset($data->replied_stage->_href) ? $data->replied_stage->_href : null,
                        'added_stage_id'=> isset($data->added_stage->id) ? $data->added_stage->id : null,
                        'added_stage_href'=> isset($data->added_stage->_href) ? $data->added_stage->_href : null,
                        'finished_stage_id'=> isset($data->finished_stage->id) ? $data->finished_stage->id : null,
                        'finished_stage_href'=> isset($data->finished_stage->_href) ? $data->finished_stage->_href : null,
                        'cadence_priority_id'=> isset($data->cadence_priority->id) ? $data->cadence_priority->id : null,
                        'cadence_priority_href'=> isset($data->cadence_priority->_href) ? $data->cadence_priority->_href : null,
                        'counts_cadence_people'=> isset($data->counts->cadence_people) ? $data->counts->cadence_people : null,
                        'counts_people_acted_on_count'=> isset($data->counts->people_acted_on_count) ? $data->counts->people_acted_on_count : null,
                        'counts_target_daily_people'=> isset($data->counts->target_daily_people) ? $data->counts->target_daily_people : null,
                        'counts_opportunities_created'=> isset($data->counts->opportunities_created) ? $data->counts->opportunities_created : null,
                        'counts_meetings_booked'=> isset($data->counts->meetings_booked) ? $data->counts->meetings_booked : null
                    ]; 
                    //insert salesloft cadence record
                    Slcadence::insert($cadence_data);   
                }
                $store_log_id .= $log_id;
        }while($page <= $total_page);
        //End date(log)
        $endDate = Carbon::now();
        Log::channel('slcadence')->notice('
        Total Pull Cadence = '.$total_data.'
        Cadence id = '.$store_log_id.'
        End Date = '.$endDate->format('Y-m-d H:i:s.uP').'
        ------------------------------------------------------------
        ');
        return 0;
    }

}
