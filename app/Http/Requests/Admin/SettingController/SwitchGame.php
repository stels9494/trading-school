<?php

namespace App\Http\Requests\Admin\SettingController;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Setting;
use App\Models\StockQuotation;

class SwitchGame extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Setting::getValueByName('status'))
        {
            return [];
        }
        $minYear = StockQuotation::getMinYear();
        $maxYear = StockQuotation::getMaxYear();
        return [
            'month_start' => 'required|integer|between:1,12',
            'month_finish' => 'required|integer|between:1,12',
            'year_start' => 'required|integer|between:'.$minYear.','.$maxYear,
            'year_finish' => 'required|integer|between:'.$minYear.','.$maxYear.'|gte:year_start',
            'month_in_minute' => 'required|integer|min:1',
        ];
    }
}
