<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CommandStock extends Model
{
    protected $table = 'command_stock';
    protected $guarded = [];

    protected $dates = [
        'time_by_exchange',
    ];

    protected $appends = [
        'action',
        'time'
    ];

    /********** RELATIONHIPS START ********************/

    	/**
    	 * Команда, которая произвела манипуляцию с акцией
    	 */
    	public function command()
    	{
    		return $this->belongsTo(Command::class);
    	}

    	/**
    	 * Акция, с которой произвела манипуляцию команда
    	 */
    	public function stock()
    	{
    		return $this->belongsTo(Stock::class);
    	}

    /********** RELATIONHIPS FINISH ********************/

    public function getTimeAttribute()
    {
        return $this->time_by_exchange->format('d.m.Y H:m');
    }

    /**
     * Действие
     *
     * @return string
     */
    public function getActionAttribute()
    {
        if ($this->count < 0)
        {
            return 'SELL';
        } else {
            return 'BUY';
        }
    }
}
