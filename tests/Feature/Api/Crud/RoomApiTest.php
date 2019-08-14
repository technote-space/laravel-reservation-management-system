<?php
declare(strict_types=1);

namespace Tests\Feature\Api\Crud;

use App\Models\Admin;
use App\Models\Room;

/**
 * Class RoomApiTest
 * @package Tests\Feature\Api\Crud
 * @group Feature
 * @group Feature.Api
 * @group Feature.Api.Crud
 */
class RoomApiTest extends BaseTestCase
{
    /** @var Admin $admin */
    private $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testIndex()
    {
        factory(Room::class, 25)->create();

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index')
        );
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'path',
                     'to',
                     'total',
                 ])
                 ->assertJsonCount(10, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                'per_page' => 5,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'path',
                     'to',
                     'total',
                 ])
                 ->assertJsonCount(5, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                'count' => 0,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(25);

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                'count'  => 20,
                'offset' => 20,
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    public function testShow()
    {
        $room     = factory(Room::class)->create();
        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.show', [
                'room' => $room->id,
            ])
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name'   => $room->name,
                     'number' => $room->number,
                     'price'  => $room->price,
                 ])
                 ->assertJsonStructure([
                     'latest_reservation',
                     'latest_usage',
                     'recent_usages',
                 ]);
    }

    public function testStore()
    {
        $this->assertFalse(Room::where('name', 'abc')->where('number', 3)->where('price', 123)->exists());

        $response = $this->actingAs($this->admin)->json(
            'POST',
            route('rooms.store'),
            [
                'rooms' => [
                    'name'   => 'abc',
                    'number' => 3,
                    'price'  => 123,
                ],
            ]
        );

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name'   => 'abc',
                     'number' => 3,
                     'price'  => 123,
                 ]);
        $this->assertTrue(Room::where('name', 'abc')->where('number', 3)->where('price', 123)->exists());
    }

    public function testUpdate()
    {
        $room = factory(Room::class)->create([
            'name'   => 'def',
            'number' => 4,
            'price'  => 234,
        ]);
        $this->assertFalse(Room::where('name', 'efg')->where('number', 5)->where('price', 345)->exists());

        $response = $this->actingAs($this->admin)->json(
            'PATCH',
            route('rooms.update', [
                'room' => $room->id,
            ]),
            [
                'rooms' => [
                    'name'   => 'efg',
                    'number' => 5,
                    'price'  => 345,
                ],
            ]
        );

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name'   => 'efg',
                     'number' => 5,
                     'price'  => 345,
                 ]);
        $this->assertTrue(Room::where('name', 'efg')->where('number', 5)->where('price', 345)->exists());
    }

    public function testDestroy()
    {
        $room = factory(Room::class)->create([
            'name'   => 'fgh',
            'number' => 6,
            'price'  => 456,
        ]);
        $this->assertTrue(Room::where('name', 'fgh')->where('number', 6)->where('price', 456)->exists());

        $response = $this->actingAs($this->admin)->json(
            'DELETE',
            route('rooms.destroy', [
                'room' => $room->id,
            ])
        );

        $response->assertStatus(200);
        $this->assertFalse(Room::where('name', 'fgh')->where('number', 6)->where('price', 456)->exists());
    }

    public function testSearch()
    {
        factory(Room::class)->create([
            'name' => 'test1',
        ]);
        factory(Room::class)->create([
            'name' => 'test2',
        ]);

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index')
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                's' => '',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                's' => 'test',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                's' => 'test1',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        $response = $this->actingAs($this->admin)->json(
            'GET',
            route('rooms.index', [
                's' => 'test1 abc',
            ])
        );
        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }
}
