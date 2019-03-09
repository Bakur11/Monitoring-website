<?php

namespace Monitoring\Contact\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendGuest;
use Monitoring\Contact\Models\Link;
use Monitoring\Contact\Models\Report;
use App\User;
use Monitoring\Contact\Models\Job;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ProcessRequestLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    protected $user;

    protected $link;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($link, $user)
    {
        $this->link = $link;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        try {
            $res = $client->get($this->link['link'], ['auth' =>  ['user', 'pass']]);
            $job = (new ProcessRequestLink($this->link, $this->user))->onQueue('default')->delay(now()->addMinutes($this->link->hour));
            $id  = app(Dispatcher::class)->dispatch($job);
            $message = $res->getBody();
            $status = $res->getStatusCode();
            Link::where('id', $this->link->id)->update(['job_id' => $id]);
            $data = [
                'link_id' => $this->link->id,
                'status_code' => $status,
                'message' => 'success'
            ];
            $report = new Report($data);
            $report->save();
        } catch (ClientException  $e) {
            $error = $e->getResponse()->getBody();
            Log::info('error = '.$error);
            $status = $e->getCode();
            $job = (new ProcessRequestLink($this->link, $this->user))->onQueue('default')->delay(now()->addMinutes($this->link->hour));
            $id  = app(Dispatcher::class)->dispatch($job);
            Link::where('id', $this->link->id)->update(['job_id' => $id]);
            $data = [
                'link_id' => $this->link->id,
                'status_code' => $status,
                'message' => $error
            ];
            $report = new Report($data);
            $report->save();
            Mail::to($this->user->email)->send(new SendGuest($error));
        }
    }
}
