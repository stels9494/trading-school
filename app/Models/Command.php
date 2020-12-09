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

    /**
     * Стоимость портфеля команды
     * 
     * @return float
     */
    public function getStocksBalanceAttribute(): float
    {
        $balance = 0;
        foreach ($this->stocks as $stock){
            $balance += $stock->count * $stock->stock->current_price;
        }
        return round($balance, 2);
    }

    /**
     * Купить указанное кол-во акций
     *
     * @return true если достаточно средств
     */
    public function buy(Stock $stock, int $count): bool
    {
        // получить средства команды
        // получить стоимость акции
        // проверить возможность покупки такого кол-ва
        $result = false;
        DB::beginTransaction();
        // акция торгуется и у команды достаточный баланс для покупки
        $currentDate = Setting::getValueByName('current_date');
        if ($stock->on_the_exchange && $stock->isQuotationByDay($currentDate) && $this->balance >= $stock->current_price * $count && Setting::getValueByName('status') && !Setting::getValueByName('is_pause'))
        {
            $this->update([
                'balance' => $this->balance - $stock->current_price * $count,
            ]);
            $this->tradingHistories()->create([
                'stock_id' => $stock->id,
                'price' => $stock->current_price,
                'count' => $count,
                'time_by_exchange' => $currentDate,
            ]);
            $result = true;
        }

        if ($result)
            DB::commit();
        else
            DB::rollback();

        return $result;
    }

    /**
     * Продать указанное кол-во акций
     *
     * @return true если достаточно средств
     */
    public function sell(Stock $stock, int $count): bool
    {
        $result = false;
        DB::beginTransaction();

        // получить кол-во таких акций у команды
        $commandHasCount = $this->tradingHistories()->where('stock_id', $stock->id)->sum('count');
        $currentDate = Setting::getValueByName('current_date');

        // акция торгуется и у команды имеется достаточное кол-во для продажи
        // пользователь должен быть командиром в этой команде
        if ($stock->on_the_exchange && $stock->isQuotationByDay($currentDate) && $commandHasCount >= $count && Setting::getValueByName('status')  && !Setting::getValueByName('is_pause'))
        {
            $this->update([
                'balance' => $this->balance + $stock->current_price * $count,
            ]);
            $this->tradingHistories()->create([
                'stock_id' => $stock->id,
                'price' => $stock->current_price,
                'count' => $count * -1,
                'time_by_exchange' => $currentDate,
            ]);
            $result = true;
        }

        if ($result)
            DB::commit();
        else
            DB::rollback();

        return $result;
    }
}
