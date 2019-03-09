<?php

namespace Monitoring\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Monitoring\Contact\Jobs\ProcessRequestLink;
//use App\Mail\SendGuest;
use Monitoring\Contact\Models\Report;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Monitoring\Contact\Models\Link;
use Monitoring\Contact\Models\Job;
use Illuminate\Contracts\Bus\Dispatcher;


class LinkController extends Controller
{
    use InteractsWithQueue;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('monitoring::links.all');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('monitoring::links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'link' => 'required|active_url',
            'hour' => 'required|integer',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response()->json([
                "status" => "error",
                "messages" => $validator->messages()
            ], 400);
        }
        $authUser = Auth::user();
        $link = new Link($data);
        $authUser->links()->save($link);

        $job = (new ProcessRequestLink($link, $authUser))->onQueue('default');
        $id  = app(Dispatcher::class)->dispatch($job);
        Link::where('id',$link->id)->update(['job_id' => $id]);
        if($link){
            $get = "Success! added link";
            return response([
                "status" => "success",
                "get" => $get
            ], 200);
        }else {
            $error = "Insert not working";
            return response([
                "status" => "error",
                "get" => $error
            ], 404);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('monitoring::links.edit')->with('id',$id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'link' => 'required|active_url',
            'hour' => 'required|integer',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return response()->json([
                "status" => "error",
                "messages" => $validator->messages()
            ], 400);
        }
        Link::find($id)->update($data);
        $get = "Success! Update link";
        return response([
            "status" => "success",
            "get" => $get
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = Link::find($id);
        $link->load('reports');
        $job = Job::find($link->job_id);
        if($link !== null){
            $link->delete();
            if($job != null) {
                $job->delete();
            }
            return response([
                "status" => "success",
            ], 200);
        }else {
            return response([
                "status" => "error",
            ], 404);
        }

    }

    public function allLinks()
    {
        $links = Link::all();
        return response([
            "status" => "success",
            "links" => $links
        ], 200);
    }

    public function getLink($id)
    {
        $link = Link::find($id);
        return response([
            "status" => "success",
            "link" => $link
        ], 200);
    }

    public function change(Request $request)
    {
        $link = $request->all();
        $authUser = Auth::user();
        Link::find($link['id'])->update($link);
        $updateLink = Link::find($link['id']);
        if($link['check'] == 0) {
            $job = Job::find($updateLink['job_id']);
            if ($job != null) {
                $job->delete();
            }
        }elseif ($link['check'] == 1){
            $link = Link::find($link['id']);
            $job = (new ProcessRequestLink($link, $authUser))->onQueue('default')->delay(now()->addMinutes($link['hour']));
            $id  = app(Dispatcher::class)->dispatch($job);
            Link::where('id',$link['id'])->update(['job_id' => $id]);
        }
    }
}

