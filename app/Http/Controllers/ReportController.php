<?php

namespace App\Http\Controllers;

use App\Report;
use File;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {

        $report = new Report();
        $events_data = $report->get_events();

        $groups_data = $report->get_groups();


        // print_r($events_data);

       // exit;


	  $fileName = 'events_datafile.json';
      File::put(public_path($fileName),$events_data);
      

      $fileName = 'groups_datafile.json';
	  File::put(public_path($fileName),$groups_data);


        return view('report',['Events' => $events_data , 'Groups' => $groups_data ]);
    }
}