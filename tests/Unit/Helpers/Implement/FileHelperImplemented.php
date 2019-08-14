<?php
declare(strict_types=1);

namespace Tests\Unit\Helpers\Implement;

use \App\Helpers\Traits\FileHelper;

/**
 * Class FileHelperImplemented
 * @package Tests\Unit\Helpers\Implement
 */
class FileHelperImplemented
{
    use FileHelper;

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
