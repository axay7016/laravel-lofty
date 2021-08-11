<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

//salesloft call api
function getSalesLoftCall($updateDate, $page, $type){
    if($type == 'backword'){
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/activities/calls.json?sort_direction=DESC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Blte%5D='.$updateDate.'');
    }else{
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/activities/calls.json?sort_direction=ASC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Bgt%5D='.$updateDate.'');
    }
        return $response;
     }
    
//salesloft calldata api
function getSalesLoftCallData($updateDate, $page, $type){
    if($type == 'backword'){
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/call_data_records.json?sort_direction=DESC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Blte%5D='.$updateDate.'');
    }else{
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/call_data_records.json?sort_direction=ASC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Bgt%5D='.$updateDate.'');
    }
        return $response;
    }

//salesloft cadence api
function getSalesLoftCadence($updateDate, $page, $type){
    if($type == 'backword'){
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/cadences.json?sort_direction=DESC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Blte%5D='.$updateDate.'');
    }else{
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/cadences.json?sort_direction=ASC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Bgt%5D='.$updateDate.'');
    }
        return $response;
    }

//salesloft user api
function getSalesLoftUsers($updateDate, $page){
        $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/users.json?sort_direction=ASC&per_page=100&page='.$page.'&include_paging_counts=true&updated_at%5Bgt%5D='.$updateDate.'');
        return $response;
    }

//salesloft people api
function getSalesLoftPeople($cadenceId,$f_date,$l_date){
    $response = Http::withToken(env("SALESLOFT_API_KEY"))->get('https://api.salesloft.com/v2/people.json?include_paging_counts=true&updated_at%5Bgte%5D='.$f_date.'&updated_at%5Blte%5D='.$l_date.'&cadence_id%5B%5D='.$cadenceId.'');
    return $response;
}
