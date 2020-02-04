<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * Class DateRequest
 * @package App\Http\Requests
 */
class DateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'date' => 'nullable|date',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'date' => __('request.misc.date'),
        ];
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return Carbon::parse(Arr::get($this->validated(), 'date') ?: now()->format('Y-m-d'));
    }
}
