<?php

namespace Tests\Integration;

use App\Model\State;
use Tests\TestCase;
use App\Model\City;

class CRUDCityTest extends TestCase
{    
    protected function setUp()
    {
        parent::setUp();

        City::truncate();
        State::truncate();
    }

    protected function tearDown()
    {
        City::truncate();
        State::truncate();

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testShouldCreateCity()
    {
        $state = factory(State::class)->create();

        $this->assertEquals(City::all()->count(), 0);

        $uri = route('city.store');

        $data = [
            'city' => [
                'name' => 'Rio de Janeiro',
                'state_id' => $state->id
            ]
        ];

        $response = $this->post($uri, $data);

        $response->assertStatus(204);

        $equipments = City::all();

        $this->assertEquals($equipments->count(), 1);

        $city = $equipments->first();

        $this->assertEquals('Rio de Janeiro', $city->name);
        $this->assertEquals($state->id, $city->state->id);
    }

    public function testShouldValidateCreateCityRequiredAttributes()
    {
        $this->assertEquals(City::all()->count(), 0);

        $uri = route('city.store');

        $data = [
            'city' => [
                'name' => null,
                'state_id' => null
            ]
        ];

        $response = $this->post($uri, $data);

        $response->assertStatus(422);

        $response->assertJson([
            'city.name' => ['The city.name field is required.'],
            'city.state_id' => ['The city.state id field is required.']
        ]);

        $this->assertEquals(City::all()->count(), 0);
    }

    public function testShouldReadCityById()
    {
        $city = factory(City::class)->create();

        $uri = route('city.show', [
            'id' => $city->id
        ]);

        $response = $this->get($uri);

        $response
            ->assertStatus(200)
            ->assertJson([
                'model' => [
                    '_id' => $city->id,
                    'name' => $city->name,
                    'state' => [
                        '_id' => $city->state->id,
                        'name' => $city->state->name,
                        'abbreviation' => $city->state->abbreviation,
                        'updated_at' => (string) $city->state->updated_at,
                        'created_at' => (string) $city->state->created_at
                    ],
                    'updated_at' => (string) $city->updated_at,
                    'created_at' => (string) $city->created_at
                ]
            ]);
    }

    public function testReadCityNotFound()
    {
        $this->assertEquals(City::all()->count(), 0);

        $uri = route('city.show', [
            'id' => '0000'
        ]);

        $response = $this->get($uri);

        $response->assertStatus(404);
    }

    public function testShouldUpdateCity()
    {
        $this->assertEquals(City::all()->count(), 0);

        $city = factory(City::class)->create();

        $this->assertEquals(City::all()->count(), 1);

        $uri = route('city.update', [
            'id' => $city->id
        ]);

        $state = factory(State::class)->create();

        $data = [
            'city' => [
                'name' => 'Rio de Janeiro',
                'state_id' => $state->id
            ]
        ];

        $response = $this->put($uri, $data);

        $response->assertStatus(204);

        $this->assertEquals(City::all()->count(), 1);

        $cityUpdated = City::find($city->id);

        $this->assertEquals('Rio de Janeiro', $cityUpdated->name);
        $this->assertEquals($state->id, $cityUpdated->state->id);

        $this->assertNotEquals($city->name, $cityUpdated->name);
        $this->assertNotEquals($city->state->id, $cityUpdated->state->id);
    }

    public function testShouldValidateUpdateCityRequiredAttributes()
    {
        $this->assertEquals(City::all()->count(), 0);

        $equip = factory(City::class)->create();

        $this->assertEquals(City::all()->count(), 1);

        $uri = route('city.update', [
            'id' => $equip->id
        ]);

        $data = [
            'city' => [
                'name' => null,
                'state_id' => null
            ]
        ];

        $response = $this->put($uri, $data);

        $response->assertStatus(422);

        $response->assertJson([
            'city.name' => ['The city.name field must have a value.'],
            'city.state_id' => ['The city.state id field must have a value.']
        ]);

        $this->assertEquals(City::all()->count(), 1);
    }

    public function testShouldValidateUpdateCityStateInvalidAttribute()
    {
        $this->assertEquals(City::all()->count(), 0);

        $equip = factory(City::class)->create();

        $this->assertEquals(City::all()->count(), 1);

        $uri = route('city.update', [
            'id' => $equip->id
        ]);

        $data = [
            'city' => [
                'state_id' => '00000'
            ]
        ];

        $response = $this->put($uri, $data);

        $response->assertStatus(422);

        $response->assertJson([
            'city.state_id' => ['The selected city.state id is invalid.']
        ]);

        $this->assertEquals(City::all()->count(), 1);
    }

    public function testUpdateCityNotFound()
    {
        $this->assertEquals(City::all()->count(), 0);

        $uri = route('city.update', [
            'id' => '0000'
        ]);

        $response = $this->put($uri);

        $response->assertStatus(404);
    }

    public function testShouldDeleteEquipmentById()
    {
        $equipment = factory(City::class)->create();

        $this->assertEquals(City::all()->count(), 1);

        $uri = route('city.destroy', [
            'id' => $equipment->id
        ]);

        $response = $this->delete($uri);

        $response->assertStatus(204);

        $this->assertEquals(City::all()->count(), 0);
    }

    public function testDeleteEquipmentNotFound()
    {
        $this->assertEquals(City::all()->count(), 0);

        $uri = route('city.destroy', [
            'id' => '0000'
        ]);

        $response = $this->delete($uri);

        $response->assertStatus(404);
    }

    public function testListAllEquipment()
    {
        factory(City::class, 3)->create();

        $this->assertCount(3, City::all());

        $uri = route('city.index');

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    '_id',
                    'name',
                    'state' => [
                        '_id',
                        'name',
                        'abbreviation',
                        'updated_at',
                        'created_at'
                    ],
                    'updated_at',
                    'created_at'
                ]
            ]);
    }

    public function testFilterCitiesByAttributes()
    {
        factory(City::class)->create([
            'name' => 'Osasco'
        ]);

        factory(City::class, 2)->create();

        $uri = route('city.filter', [
            'name' => 'osa'
        ]);

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(1, 'cities');
    }

    public function testFilterCitiesByRelatedAttributes()
    {
        $state = factory(State::class)->create([
            'name' => 'São Paulo'
        ]);

        factory(City::class)->create([
            'name' => 'Osasco',
            'state_id' => $state->id
        ]);

        factory(City::class)->create([
            'name' => 'São Paulo',
            'state_id' => $state->id
        ]);

        factory(City::class, 2)->create();

        $uri = route('city.filter', [
            'state_name' => 'paulo'
        ]);

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(2, 'cities');
    }

    public function testFilterCitiesByAllAttributeTypesAttributes()
    {
        $state = factory(State::class)->create([
            'name' => 'São Paulo'
        ]);

        factory(City::class)->create([
            'name' => 'Osasco',
            'state_id' => $state->id
        ]);

        factory(City::class)->create([
            'name' => 'São Paulo',
            'state_id' => $state->id
        ]);

        factory(City::class, 2)->create();

        $uri = route('city.filter', [
            'state_name' => 'paulo',
            'name' => 'paulo'
        ]);

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(1, 'cities');
    }
}
