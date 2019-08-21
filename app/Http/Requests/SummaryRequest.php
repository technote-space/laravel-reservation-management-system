<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SummaryRequest
 * @package App\Http\Requests
 */
class SummaryRequest extends FormRequest
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
            'start_date' => 'required|filled|date',
            'end_date'   => 'required|filled|date',
            'type'       => 'nullable|string',
            'room_id'    => 'nullable|integer|exists:rooms,id',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'start_date' => __('request.summary.start_date'),
            'end_date'   => __('request.summary.end_date'),
            'type'       => __('request.summary.type'),
            'room_id'    => __('request.summary.room_id'),
        ];
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return array_merge([
            'type' => 'monthly',
        ], $this->validated());
    }
}
