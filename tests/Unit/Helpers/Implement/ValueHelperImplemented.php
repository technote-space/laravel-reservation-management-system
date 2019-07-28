<?php
declare(strict_types=1);

namespace Tests\Unit\Helpers\Implement;

use \App\Helpers\Traits\ValueHelper;

/**
 * Class ValueHelperImplemented
 * @package Tests\Unit\Helpers\Implement
 */
class ValueHelperImplemented
{
    use ValueHelper;

    /**
     * @param  string  $name
     * @param  array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, $name], $arguments);
    }
}
