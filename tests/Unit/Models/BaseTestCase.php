<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class BaseTestCase
 * @package Tests\Unit\Models
 * @SuppressWarnings(PMD.TooManyPublicMethods)
 */
abstract class BaseTestCase extends \Tests\BaseTestCase
{
    abstract protected function getTarget(): string;

    protected function getTargetId(): string
    {
        return $this->getTarget().'_id';
    }

    /**
     * @param $class
     * @param $target
     *
     * @dataProvider belongToDataProvider
     */
    public function testBelongTo($class, $target)
    {
        $name = $this->getTarget();
        $this->assertInstanceOf($class, static::$$name->$target);
        $this->assertEquals(static::$$target->id, static::$$name->$target->id);
    }

    public function belongToDataProvider(): array
    {
        return [];
    }

    /**
     * @param $class
     * @param $target
     * @param  int  $count
     *
     * @dataProvider belongToManyDataProvider
     */
    public function testBelongsToMany($class, $target, $count = 2)
    {
        $name = $this->getTarget();
        $this->assertInstanceOf(Collection::class, static::$$name->$target);
        $this->assertEquals($count, static::$$name->$target->count());
        $this->assertInstanceOf($class, static::$$name->$target->get(0));
        for ($i = 0; $i < $count; $i++) {
            $model = (static::$$target)[$i];
            $this->assertEquals($model->id, static::$$name->$target->get($i)->id);
        }
    }

    public function belongToManyDataProvider(): array
    {
        return [];
    }

    /**
     * @param $class
     * @param $target
     *
     * @dataProvider  hasOneDataProvider
     */
    public function testHasOne($class, $target)
    {
        $name = $this->getTarget();
        $this->assertInstanceOf($class, static::$$name->$target);
        $this->assertEquals(static::$$target->id, static::$$name->$target->id);
    }

    public function hasOneDataProvider(): array
    {
        return [];
    }

    /**
     * @param $class
     * @param $target
     * @param  int  $count
     *
     * @dataProvider hasManyDataProvider
     */
    public function testHasMany($class, $target, $count = 2)
    {
        $name = $this->getTarget();
        $this->assertInstanceOf(Collection::class, static::$$name->$target);
        $this->assertEquals($count, static::$$name->$target->count());
        $this->assertInstanceOf($class, static::$$name->$target->get(0));
        for ($i = 0; $i < $count; $i++) {
            $model = (static::$$target)[$i];
            $this->assertEquals($model->id, static::$$name->$target->get($i)->id);
        }
    }

    public function hasManyDataProvider(): array
    {
        return [];
    }

    /**
     * @param $class
     * @param $target
     *
     * @dataProvider morphOneDataProvider
     */
    public function testMorphOne($class, $target)
    {
        $name = $this->getTarget();
        $this->assertInstanceOf($class, static::$$name->$target);
        $this->assertNotEmpty(static::$$name->$target->id);
    }

    public function morphOneDataProvider(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $name = $this->getTarget();
        $key  = $this->getTargetId();
        /** @var Eloquent $class */
        $class  = get_class(static::$$name);
        $pid    = static::$$name->id;
        $morphs = [];
        foreach ($this->morphOneDataProvider() as $item) {
            $morphs[] = [
                $item[0],
                static::$$name->{$item[1]}->id,
            ];
        }

        $class::find($pid)->delete();
        $deleted = array_merge(
            Arr::pluck($this->hasManyDataProvider(), 1),
            Arr::pluck($this->hasOneDataProvider(), 1)
        );
        $exists  = array_merge(
            Arr::pluck($this->belongToDataProvider(), 1),
            Arr::pluck($this->belongToManyDataProvider(), 1)
        );

        collect($deleted)->each(function ($item) {
            /** @var Eloquent $class */
            if (static::$$item instanceof Model) {
                $class = get_class(static::$$item);
                $this->assertEmpty($class::find(static::$$item->id));
            } else {
                foreach (static::$$item as $i) {
                    $class = get_class($i);
                    $this->assertEmpty($class::find($i->id));
                }
            }
        });

        collect($morphs)->each(function ($item) {
            /** @var Eloquent $class */
            $class = $item[0];
            $this->assertEmpty($class::find($item[1]));
        });

        foreach (Arr::pluck($this->belongToManyDataProvider(), 2) as $item) {
            /** @var Eloquent $item */
            $this->assertEmpty($item::where($key, $pid)->exists());
        }

        collect($exists)->each(function ($item) {
            if (static::$$item instanceof Model) {
                /** @var Eloquent $class */
                $class = get_class(static::$$item);
                $this->assertNotEmpty($class::find(static::$$item->id));
            } else {
                foreach (static::$$item as $i) {
                    /** @var Eloquent $class */
                    $class = get_class($i);
                    $this->assertNotEmpty($class::find($i->id));
                }
            }
        });
    }
}
