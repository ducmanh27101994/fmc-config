<?php

namespace Fmcpay\Config\Model;

use Illuminate\Database\Eloquent\Model;

class GeneralConfiguration extends Model
{
    protected $table = 'general_configuration';
    protected $fillable = [
        'start_time',
        'end_time',
        'status',
        'configuration_type'
    ];

    public function activate()
    {
        $this->status = 1;
        $this->save();
    }

    public function deactivate()
    {
        $this->status = 0;
        $this->save();
    }
}
