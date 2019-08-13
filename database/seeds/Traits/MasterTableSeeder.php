<?php

namespace Seeds\Traits;

use App\Helpers\Traits\FileHelper;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Trait MasterTableSeeder
 * @package Seeds\Traits
 * @mixin Seeder
 * @mixin FileHelper
 */
trait MasterTableSeeder
{
    /** @var string $tableName */
    private $tableName;

    /**
     * @return string|Eloquent
     */
    abstract protected function getTarget();

    /**
     * @param  array  $row
     *
     * @return array
     */
    abstract protected function converter(array $row): array;

    /**
     * @return string
     * @SuppressWarnings(PHPMD.MissingImport)
     */
    private function getTableName()
    {
        if (! $this->tableName) {
            $class = $this->getTarget();
            /** @var Model $instance */
            $instance        = new $class;
            $this->tableName = $instance->getTable();
        }

        return $this->tableName;
    }

    /**
     * @return string
     */
    private function getCsvFilename(): string
    {
        return $this->getTableName().'.json';
    }

    /**
     * @return string
     */
    private function getCsvPath(): string
    {
        return resource_path('seed').DIRECTORY_SEPARATOR.$this->getCsvFilename();
    }

    /**
     * import
     */
    protected function import()
    {
        if (! file_exists($this->getCsvPath())) {
            return;
        }

        $tableName = $this->getTableName();
        $this->command->info('[Start] import data: '.$tableName);

        $this->loadJson($this->getCsvPath())->each(function ($row) {
            $item = $this->converter($row);
            if (is_array($item)) {
                factory($this->getTarget())->create($item);
            }
        });

        $this->command->info('[End] import data: '.$tableName);
    }
}
