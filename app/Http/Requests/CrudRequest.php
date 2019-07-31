<?php

namespace App\Http\Requests;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * Class CrudRequest
 * @package App\Http\Requests
 */
abstract class CrudRequest extends FormRequest
{
    /** @var string[]|Eloquent[] $models */
    private $models;

    /** @var string[] $tables */
    private $tables;

    /**
     * @return string|Eloquent
     */
    abstract protected function getTarget();

    /**
     * @return array
     */
    protected function getSubTargets(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    abstract protected function isUpdate(): bool;

    /**
     * @return array
     */
    protected function getTargets()
    {
        return array_merge([$this->getTarget()], array_values($this->getSubTargets()));
    }

    /**
     * @param  string  $target
     *
     * @return Eloquent|string
     * @throws Throwable
     */
    private function getModel(string $target)
    {
        if (! isset($this->models[$target])) {
            $this->models[$target] = $target;
            throw_if(! class_exists($target), Exception::class, "Class not exists: [{$target}]");
            throw_if($target instanceof Model, Exception::class, "Class is not Model: [{$target}]");
        }

        return $this->models[$target];
    }

    /**
     * @param  string  $target
     *
     * @return string
     * @throws Throwable
     */
    protected function getTable(string $target): string
    {
        if (! isset($this->tables[$target])) {
            $this->tables[$target] = $this->getInstance($target)->getTable();
        }

        return $this->tables[$target];
    }

    /**
     * @param  string  $target
     *
     * @return Model
     * @throws Throwable
     */
    private function getInstance(string $target)
    {
        $class = $this->getModel($target);

        return new $class;
    }

    /**
     * @return string
     * @throws Throwable
     */
    protected function getForeignKey()
    {
        return $this->getInstance($this->getTarget())->getForeignKey();
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function rules(): array
    {
//        logger(print_r(collect($this->getTargets())->flatMap(function ($target) {
//            return $this->getTableRules($target);
//        })->toArray(), true));

        return collect($this->getTargets())->flatMap(function ($target) {
            return $this->getTableRules($target);
        })->toArray();
    }

    /**
     * @param  string  $target
     *
     * @return array
     * @throws Throwable
     */
    protected function getTableRules(string $target): array
    {
        $foreignKey = $this->getForeignKey();

        return collect(DB::connection()->getDoctrineSchemaManager()->listTableColumns($this->getTable($target)))->filter(function (Column $column) use ($foreignKey) {
            return ! in_array($column->getName(), [
                'id',
                'created_at',
                'updated_at',
                $foreignKey,
            ], true);
        })->mapWithKeys(function (Column $column) use ($target) {
            return ["{$this->getTable($target)}.{$column->getName()}" => $this->getRules($target, $column)];
        })->toArray();
    }

    /**
     * @param  string  $target
     * @param  Column  $column
     *
     * @return array
     * @throws Throwable
     */
    protected function getRules(string $target, Column $column): array
    {
        $rules = [];
        if ($column->getNotnull() && is_null($column->getDefault())) {
            if ($this->isUpdate()) {
                $rules['filled'] = 'filled';
            } else {
                $rules['required'] = 'required';
            }
        }
        if ($column->getUnsigned()) {
            $rules['min'] = 'min:0';
        }
        if ($column->getLength()) {
            $rules['max'] = 'max:'.$column->getLength();
        }
        $rules = $this->getNameRules($rules, $column->getName());

        $rules = $this->getTypeRules($rules, $column->getType());

        return $this->filterRules($rules, "{$this->getTable($target)}.{$column->getName()}", $column);
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     *
     * @return array
     */
    private function getNameRules(array $rules, string $name)
    {
        if (stristr($name, 'email') !== false) {
            $rules['email'] = 'email';
        }
        if (stristr($name, 'url') !== false) {
            $rules['url'] = 'url';
        }
        if (stristr($name, 'phone') !== false) {
            $rules['regex:phone'] = 'regex:/^\d{2,4}-?\d{2,4}-?\d{3,4}$/u';
        }
        if (preg_match('#\A(\w+)_id\z#', $name, $matches)) {
            $table           = Str::snake(Str::pluralStudly($matches[1]));
            $rules['exists'] = "exists:{$table},id";
        }
        if (stristr($name, 'kana') !== false) {
            $rules['regex:kana'] = 'regex:/^[ァ-ヴー・ 　]+$/u';
        }
        if (stristr($name, 'zip_code') !== false || stristr($name, 'postal_code') !== false) {
            $rules['regex:zip_code'] = 'regex:/^\d{3}-\d{4}$|^\d{3}-\d{2}$|^\d{3}$|^\d{5}$|^\d{7}$/u';
        }

        return $rules;
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     * @param  Column  $column
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function filterRules(/** @noinspection PhpUnusedParameterInspection */ array $rules, string $name, Column $column): array
    {
        return $rules;
    }

    /**
     * @param  array  $rules
     * @param  Type  $type
     *
     * @return array
     */
    protected function getTypeRules(array $rules, Type $type)
    {
        $normalized = null;
        if (in_array($type->getName(), [
            Type::BOOLEAN,
        ], true)) {
            $normalized = 'Boolean';
        } elseif (in_array($type->getName(), [
            Type::INTEGER,
            Type::BIGINT,
            Type::SMALLINT,
        ], true)) {
            $normalized = 'Int';
        } elseif (in_array($type->getName(), [
            Type::FLOAT,
        ], true)) {
            $normalized = 'Numeric';
        } elseif (in_array($type->getName(), [
            Type::DATETIME,
            Type::DATETIME_IMMUTABLE,
            Type::DATETIMETZ,
            Type::DATETIMETZ_IMMUTABLE,
            Type::DATE,
            Type::DATE_IMMUTABLE,
        ], true)) {
            $normalized = 'Date';
        } elseif (in_array($type->getName(), [
            Type::TIME,
            Type::TIME_IMMUTABLE,
        ], true)) {
            $normalized = 'Time';
        } elseif (in_array($type->getName(), [
            Type::STRING,
            Type::TEXT,
        ], true)) {
            $normalized = 'String';
        }

        if ($normalized) {
            $function = "get{$normalized}TypeRules";

            return $this->$function($rules);
        }

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getBooleanTypeRules(array $rules)
    {
        $rules['boolean']  = 'boolean';
        $rules['nullable'] = 'nullable';
        unset($rules['required']);
        unset($rules['filled']);

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getIntTypeRules(array $rules)
    {
        $rules['integer'] = 'integer';

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getNumericTypeRules(array $rules)
    {
        $rules['numeric'] = 'numeric';

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getDateTypeRules(array $rules)
    {
        $rules['date'] = 'date';

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getTimeTypeRules(array $rules)
    {
        $rules['time'] = 'date_format:H:i';

        return $rules;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     *
     * @param  array  $rules
     *
     * @return array
     */
    private function getStringTypeRules(array $rules)
    {
        $rules['string'] = 'string';

        return $rules;
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function attributes()
    {
        //        logger(print_r(collect($this->getTargets())->flatMap(function ($target) {
        //            return $this->getTableAttributes($target);
        //        })->toArray(), true));

        return collect($this->getTargets())->flatMap(function ($target) {
            return $this->getTableAttributes($target);
        })->toArray();
    }

    /**
     * @param  string  $target
     *
     * @return array
     * @throws Throwable
     */
    protected function getTableAttributes(string $target): array
    {
        return collect($this->getInstance($target)->getConnection()->getDoctrineSchemaManager()->listTableColumns($this->getTable($target)))->filter(function (Column $column) {
            return ! in_array($column->getName(), [
                'id',
                'created_at',
                'updated_at',
            ], true);
        })->mapWithKeys(function (Column $column) use ($target) {
            return ["{$this->getTable($target)}.{$column->getName()}" => $this->filterAttribute($column->getComment() ?? $column->getName())];
        })->toArray();
    }

    /**
     * @param  string  $attr
     *
     * @return string
     */
    protected function filterAttribute(string $attr): string
    {
        return $attr;
    }

    /**
     * @param  string  $target
     * @param  array  $merge
     *
     * @return array
     * @throws Throwable
     */
    protected function getSaveData(string $target, array $merge = []): array
    {
        return $this->filterSaveData(array_merge(Arr::get($this->validated(), $this->getTable($target), []), $merge), $target);
    }

    /**
     * @param  array  $attrs
     * @param  string  $target
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function filterSaveData(/** @noinspection PhpUnusedParameterInspection */ array $attrs, string $target): array
    {
        return $attrs;
    }

    /**
     * @return Model
     * @throws Throwable
     */
    public function store(): Model
    {
        throw_if($this->isUpdate(), Exception::class, 'Request is for update');

        return $this->createRecord();
    }

    /**
     * @param  int  $primaryId
     *
     * @return Model
     * @throws Throwable
     */
    public function update($primaryId): Model
    {
        throw_if(! $this->isUpdate(), Exception::class, 'Request is not for update');

        return $this->updateRecord($primaryId);
    }

    /**
     * @return Eloquent|Model
     * @throws Throwable
     */
    protected function createRecord(): Model
    {
        $record     = $this->getModel($this->getTarget())::create($this->getSaveData($this->getTarget()));
        $foreignKey = $this->getForeignKey();
        collect($this->getSubTargets())->each(function ($target, $relation) use ($record, $foreignKey) {
            $record->$relation()->create(
                $this->getSaveData(
                    $target,
                    [
                        $foreignKey => $record->getAttribute('id'),
                    ]
                )
            );
        });

        return $record;
    }

    /**
     * @param  int  $primaryId
     *
     * @return Model
     * @throws Throwable
     */
    protected function updateRecord($primaryId): Model
    {
        $record = $this->getModel($this->getTarget())::findOrFail($primaryId);
        $record->fill($this->getSaveData($this->getTarget()))->save();
        $foreignKey = $this->getForeignKey();
        collect($this->getSubTargets())->each(function ($target, $relation) use ($record, $foreignKey) {
            $record->$relation()->save($this->getModel($target)::updateOrCreate(
                [
                    $foreignKey => $record->getAttribute('id'),
                ],
                $this->getSaveData($target)
            ));
        });

        return $record;
    }
}
