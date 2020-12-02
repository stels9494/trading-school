<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Quotation extends Model
{
    protected $guarded = [];

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
