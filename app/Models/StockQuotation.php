<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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

    /**
     * Минимальный год для котировок
     * 
     * @return int
     */
    public static function getMinYear(): int
    {
        return (new Carbon(self::min('datetime')))->year;
    }

    /**
     * Максимальный год для 
     * 
     * @return int
     */
    public static function getMaxYear(): int
    {
        return (new Carbon(self::max('datetime')))->year;
    }
}
