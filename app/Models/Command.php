<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Command extends Model
{
    protected $table = 'commands';
    protected $guarded = [];

    /*********** RELATIONSHIPS START ********************/

    	/**
    	 * Список пользователей в команде
    	 * 
    	 * @return Illuminate\Database\Eloquent\Builder
    	 */
    	public function users()
    	{
    		return $this->hasMany(User::class);
    	}

    	/**
    	 * Список операция с акциями текущей команды
    	 * 
    	 * @return Illuminate\Database\Eloquent\Builder
    	 */
    	public function tradingHistories()
    	{
    		return $this->hasMany(CommandStock::class);
    	}

    /*********** RELATIONSHIPS FINISH ********************/



    /**
     * Существование командира в команде
     * 
     * @return bool
     */
    public function getIsCommanderAttribute(): bool
    {
        return $this->users()->whereHas('roles', function ($roles) {
            $roles->where('name', 'commander');
        })->exists();
    }

    /**
     * список акций теккущей команды
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getStocksAttribute(): Collection
    {
        return $this
            ->tradingHistories()
            ->select(
                'stock_id',
                DB::raw('SUM(count) as count')
            )
            ->groupBy('stock_id')
            ->having('count', '>', 0)
            ->with('stock')
            ->get();
    }
}
