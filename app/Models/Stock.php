<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminat\Http\UploadedFile;

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

	/********** RELATIONSHIPS FINISH ********************/

	/**
	 * 
	 * 
	 * @return true если котировки импортировались
	 */
	public function importQuotations(UploadedFile $file): bool
	{
		$result = false;
		if ($file->isValid())
		{

		}
		return $result;
	}
}
