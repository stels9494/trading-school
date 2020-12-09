<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
	protected $guarded = [];

	/********** RELATIONSHIPS START ********************/

		/**
		 * Команды, которые имели дело с этой акцией
		 * 
		 */
		public function commands()
		{
			return $this->belongsToMany(Command::class);
		}

		/**
		 * Котировки акции
		 */
		public function quotations()
		{
			return $this->hasMany(StockQuotation::class);
		}

	/********** RELATIONSHIPS FINISH ********************/



	/**
	 * Есть ли котировки для акции
	 * 
	 * @return bool
	 */
	public function getIsQuotationsAttribute(): bool
	{
		return (bool) $this->quotations()->count();
	}

	/**
	 * текущая стоимость акции
	 * 
	 * @return float
	 */
	public function getCurrentPriceAttribute(): float
	{
		$quotation = $this->quotations()->where('datetime', Setting::getValueByName('current_date'))->first();
		return $quotation ? $quotation->price : 0;
	}

	/**
	 * 
	 * 
	 * @return true если котировки импортировались
	 */
	public static function importQuotations(UploadedFile $file): bool
	{
		$startYearIndexSymbol = 'A';
		$startYearIndexNumber = 3;
		$stepYearIndex = 13;

		$startStockIndexSymbol = 'B';
		$stoclIndexNumber = 2;



		$result = false;

		DB::beginTransaction();
		if ($file->isValid())
		{
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($file->path());


			for ($i = $startStockIndexSymbol; $stockName = $spreadsheet->getActiveSheet()->getCell($i.$stoclIndexNumber)->getValue(); $i++)
			{
				$stock = Stock::create([
					'name' => $stockName,
				]);
				for ($j = $startYearIndexNumber; $year = $spreadsheet->getActiveSheet()->getCell($startYearIndexSymbol.$j)->getValue() ; $j += $stepYearIndex)
				{

					if (((int) $year) < 1970) continue;
					for ($p = $j + 1; $price = $spreadsheet->getActiveSheet()->getCell($i.$p)->getValue(); $p++)
					{
						$month = $spreadsheet->getActiveSheet()->getCell($startYearIndexSymbol.$p)->getValue();
						$datetime = new Carbon();
						$datetime
							->setYear($year)
							->setMonth((int) $month)
							->setDay(1)
							->setTime(12, 0, 1);

						$stock->quotations()->create([
							'price' => (double) $price,
							'datetime' => $datetime,
						]);
					}

				}
			}

			$result = true;
		}

		if ($result)
			DB::commit();
		else
			DB::rollback();

		return $result;
	}

	public function importQuotationsForStock(UploadedFile $file): bool
	{
		$startYearIndexSymbol = 'A';
		$startYearIndexNumber = 3;
		$stepYearIndex = 13;

		$startStockIndexSymbol = 'B';
		$stoclIndexNumber = 2;



		$result = false;

		DB::beginTransaction();
		if ($file->isValid())
		{
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($file->path());

			$this->quotations()->delete();
			for ($j = $startYearIndexNumber; $year = $spreadsheet->getActiveSheet()->getCell($startYearIndexSymbol.$j)->getValue() ; $j += $stepYearIndex)
			{
				if (((int) $year) < 1970) continue;
				for ($p = $j + 1; $price = $spreadsheet->getActiveSheet()->getCell($startStockIndexSymbol.$p)->getValue(); $p++)
				{
					$month = $spreadsheet->getActiveSheet()->getCell($startYearIndexSymbol.$p)->getValue();
					$datetime = new Carbon();
					$datetime
						->setYear($year)
						->setMonth((int) $month)
						->setDay(1)
						->setTime(12, 0, 1);
					$this->quotations()->create([
						'price' => (double) $price,
						'datetime' => $datetime,
					]);
				}

			}

			$result = true;
		}

		if ($result)
			DB::commit();
		else
			DB::rollback();

		return $result;
	}

	/**
	 * Есть ли котировка за указанную дату
	 * 
	 * @return true если котировка на этот день имеется
	 */
	public function isQuotationByDay(Carbon $datetime)
	{
		return $this->quotations()->where('datetime', $datetime)->exists();
	}
}
