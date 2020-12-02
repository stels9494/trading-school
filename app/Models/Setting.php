<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Setting extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    /**
     * get-мутатор поля value
     * 
     * @return mixed
     */
    public function getValueAttribute()
    {
    	$value = null;

    	switch ($this->type)
    	{
    		case 'bool':
    			$value = ($this->value == '1');
    			break;

    		case 'integer':
    			$value = (int) $this->value;
    			break;

    		case 'string':
    			$value = $this->value;
    			break;

    		case 'datetime':
    			$value = new Carbon($this->value);
    			break;
    	}

    	return $value;
    }

    /**
     * set-мутатор поля value
     * 
     * @return void
     */
    public function setValueAttribute($value): void
    {
    	switch ($this->type)
    	{
    		case 'bool':
            case 'integer':
            case 'string':
            case 'datetime':
    			$this->attributes['value'] = (string) $value;
    			break;    	
        }
    }

    /**
     * Возвращает наструюку по названию
     * 
     * @param string $name
     * @return App\Models\Setting
     */
    public static function getByName(string $name): ?self
    {
        return self::whereName($name)->first();
    }

    /**
     * возвращает значение модели настройки по названию
     * 
     * @param string $name
     * @return mixed
     */
    public static function getValueByName(string $name)
    {
        $model = self::getByName($name);
        return $model ? $model->value : '';
    }

    /**
     * Изменение значения настройки
     * 
     * @param string $name
     * @param $value
     * @return true если настройка была изменена
     */
    public static function setValueByName(string $name, $value): bool
    {
        $model = self::where('name', $name)->first();
        return $model && $model->update(['value' => $value]);
    }
}
