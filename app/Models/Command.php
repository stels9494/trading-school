<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Command extends Model
{
    protected $table = 'commands';
    protected $guarded = [];

    protected $appends = [
        'stocks_balance',
        'stocks_count',
    ];

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


    public static function export()
    {
        $spreadsheet = new Spreadsheet();

        $writer = new Writer($spreadsheet);

        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->getCell('A1')->setValue('Команда');
        $worksheet->getCell('B1')->setValue('Баланс');
        $worksheet->getCell('C1')->setValue('В акциях');
        $worksheet->getCell('D1')->setValue('Итог');

        $startRow = 2;

        foreach (self::all() as $command)
        {
            $worksheet->getCell('A'.$startRow)->setValue($command->name);
            $worksheet->getCell('B'.$startRow)->setValue($command->balance);
            $worksheet->getCell('C'.$startRow)->setValue($command->stocks_balance);
            $worksheet->getCell('D'.$startRow)->setValue($command->balance + $command->stocks_balance);
            $startRow++;
        }

        return $writer;
    }


    /**
     * Существование капитана в команде
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

    public function getStocksCountAttribute()
    {
        return $this->stocks->count() ? $this->stocks->sum('count') : 0;
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
        // пользователь должен быть капитаном в этой команде
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


    /**
     * Импорт пользователей из файла в команду
     * 
     * @param Illuminate\Http\UploadedFile $file
     * @return void
     */
    public function importUsers(UploadedFile $file): void
    {
        $firstnameColumn = 'A';
        $lastnameColumn = 'B';
        $patronymicColumn = 'C';
        $emailColumn = 'D';
        $loginColumn = 'E';
        $passwordColumn = 'F';
        $startRowNum = 2;


        if ($file->isValid())
        {
            $reader = new Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->path());

            // цикл по строчкам, пока не закончатся логины
            for ($i = $startRowNum; $login = $spreadsheet->getActiveSheet()->getCell($loginColumn.$i)->getValue(); $i++)
            {
                $firstname = $spreadsheet->getActiveSheet()->getCell($firstnameColumn.$i)->getValue();
                $lastname = $spreadsheet->getActiveSheet()->getCell($lastnameColumn.$i)->getValue();
                $patronymic = $spreadsheet->getActiveSheet()->getCell($patronymicColumn.$i)->getValue();
                $email = $spreadsheet->getActiveSheet()->getCell($emailColumn.$i)->getValue();
                $password = $spreadsheet->getActiveSheet()->getCell($passwordColumn.$i)->getValue();

                if (!User::whereLogin($login)->exists() && strlen($password) > 1 && strlen($password) < 255)
                {
                    $this->users()->create([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'patronimyc' => $patronymic,
                        'email' => $email,
                        'login' => $login,
                        'password' => $password,
                    ]);
                }

            }

        }
    }


}
