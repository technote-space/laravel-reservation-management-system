<?php

namespace Seeds\Traits;

trait SeederHelper
{
    /**
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    private function getConfigValue($key, $default = null)
    {
        return config("seeder.{$key}", $default);
    }

    /**
     * @param $key
     *
     * @return int
     */
    private function getRand($key)
    {
        $min = $this->getConfigValue($key.'_min', 0) - 0;
        $max = $this->getConfigValue($key.'_max', 0) - 0;
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
     * @param $default
     *
     * @return int|mixed
     */
    protected function getValue($key, $default = null)
    {
        $value = $this->getConfigValue($key, $default);
        if (isset($value)) {
            if (ctype_digit($value)) {
                return $value - 0;
            }

            return $value;
        }

        return $this->getRand($key);
    }
}
