<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SearchRequest
 * @package App\Http\Requests
 */
abstract class SearchRequest extends FormRequest
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
        return array_merge([
            's'        => 'nullable|string',
            'count'    => 'nullable|integer|min:0',
            'offset'   => 'nullable|integer|min:0',
            'per_page' => 'nullable|integer|min:1',
        ], $this->additionalRules());
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array_merge([
            's'        => __('request.search.s'),
            'count'    => __('request.search.count'),
            'offset'   => __('request.search.offset'),
            'per_page' => __('request.search.per_page'),
        ], $this->additionalAttributes());
    }

    /**
     * @return array
     */
    protected function additionalRules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function additionalAttributes(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->filterConditions($this->validated());
    }

    /**
     * @param  array  $validated
     *
     * @return array
     */
    protected function filterConditions(array $validated)
    {
        if (isset($validated['s'])) {
            $validated['s'] = str_replace(['　', "\r", "\n"], ' ', $validated['s']);
            $validated['s'] = trim($validated['s']);
            if ('' === $validated['s']) {
                unset($validated['s']);
            } else {
                $validated['s'] = collect(explode(' ', $validated['s']))
                    ->filter()
                    ->unique()
                    ->map(function ($item) {
                        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $item);
                    })
                    ->toArray();
            }
        }

        return $validated;
    }
}
