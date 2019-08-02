<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    Function get_events(){

    $events = DB::table('events')
    ->where('event_type', 'event')
    ->select(DB::raw("COUNT(*) as count_row"))
    ->selectRaw('YEAR(created) as date')
    ->groupBy(DB::raw("year(created)"))
    ->get();


    return json_encode($events);

    }
    Function get_groups(){


        $groups = DB::table('groups')
                 ->select('type',DB::raw('count(*) as total'))
                 //->selectRaw('YEAR(created) as date')
                 ->groupBy('type')
                 ->get();

        return json_encode($groups);

    }
}
