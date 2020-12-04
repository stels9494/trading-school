<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StockQuotation extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    protected $dates = [
        'datetime'
    ];

    /********** RELATIONSHIPS START ********************/

    	/**
    	 * Акция, которой принадлежит котировка
    	 *
    	 */
    	public function stocks()
    	{
    		return $this->belongsTo(Stock::class);
    	}

    /********** RELATIONSHIPS FINISH ********************/
}
