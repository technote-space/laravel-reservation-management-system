<?php

namespace Seeds\Traits;

use App\Helpers\Traits\FileHelper;
use Illuminate\Support\Arr;

/**
 * Trait SeederHelper
 * @package Seeds\Traits
 * @mixin FileHelper
 */
trait SeederHelper
{
    private $env;

    /**
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    private function getEnvValue($key, $default = null)
    {
        if (! $this->env) {
            $this->env = Arr::get($this->loadJson(resource_path('seed').DIRECTORY_SEPARATOR.'env.json'), config('seed.env'));
        }

        return Arr::get($this->env, $key, $default);
    }

    /**
     * @param $key
     *
     * @return int|null
     */
    private function getRand($key)
    {
        $min = $this->getEnvValue($key.'.min');
        $max = $this->getEnvValue($key.'.max');
        if (! isset($min) || ! isset($max)) {
            return null;
        }
        if ($min == $max) {
            return $min;
        }
        if ($min > $max) {
            return rand($max, $min);
        }

        return rand($min, $max);
    }

    /**
     * @param $key
     *
     * @return int|mixed
     */
    protected function getValue($key)
    {
        $value = $this->getRand($key);
        if (isset($value)) {
            return $value;
        }

        $value = $this->getEnvValue($key);
        if (isset($value)) {
            if (ctype_digit($value)) {
                return $value - 0;
            }

            return $value;
        }

        return 0;
    }
}
