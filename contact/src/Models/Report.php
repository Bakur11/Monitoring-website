<?php

namespace Monitoring\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $table = "reports";
    /**
     *
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'link_id', 'status_code', 'message'
    ];

    public function link()
    {
        return $this->belongsTo('Monitoring\Contact\Models\Link');
    }
}
