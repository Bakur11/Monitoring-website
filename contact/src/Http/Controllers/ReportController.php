<?php

namespace Monitoring\Contact\Http\Controllers;

use Monitoring\Contact\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReportController extends Controller
{
    public function linkReports($id)
    {
        return view('monitoring::reports')->with('id', $id);
    }

    public function reports($id)
    {
        $reports = Report::where('link_id', $id)->get();
        return response([
            "status" => "success",
            "reports" => $reports
        ], 200);
    }
}
