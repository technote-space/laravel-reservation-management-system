<?php
declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface Searchable
{
    /**
     * @param  array  $conditions
     *
     * @return Model|Builder|\App\Models\Traits\Searchable
     */
    public function search(array $conditions);
}
