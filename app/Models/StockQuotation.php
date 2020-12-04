<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StockQuotation extends Model
{
    protected $guarded = [];

    /********** RELATIONSHIPS START ********************/

        public $timestamps = false;

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
