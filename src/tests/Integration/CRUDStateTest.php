<?php

namespace Tests\Integration;

use App\Model\City;
use App\Model\State;
use Tests\TestCase;

class CRUDStateTest extends TestCase
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

    public function testShouldCreateState()
    {
        $this->assertEquals(State::all()->count(), 0);

        $uri = route('state.store');

        $data = [
            'state' => [
                'name' => 'Rio de Janeiro',
                'abbreviation' => 'RJ'
            ]
        ];

        $response = $this->post($uri, $data);

        $response->assertStatus(204);

        $locations = State::all();

        $this->assertEquals($locations->count(), 1);

        $state = $locations->first();

        $this->assertEquals('Rio de Janeiro', $state->name);
        $this->assertEquals('RJ', $state->abbreviation);
    }

    public function testShouldValidateCreateStateRequiredAttributes()
    {
        $this->assertEquals(State::all()->count(), 0);

        $uri = route('state.store');

        $data = [
            'state' => [
                'name' => null,
                'abbreviation' => null
            ]
        ];

        $response = $this->post($uri, $data);

        $response->assertStatus(422);

        $response->assertJson([
            'state.name' => ['The state.name field is required.'],
            'state.abbreviation' => ['The state.abbreviation field is required.']
        ]);

        $this->assertEquals(State::all()->count(), 0);
    }

    public function testShouldReadStateById()
    {
        $state = factory(State::class)->create();

        $uri = route('state.show', [
            'id' => $state->id
        ]);

        $response = $this->get($uri);

        $response
            ->assertStatus(200)
            ->assertJson([
                'model' => [
                    '_id' => $state->id,
                    'name' => $state->name,
                    'abbreviation' => $state->abbreviation,
                    'updated_at' => (string) $state->updated_at,
                    'created_at' => (string) $state->created_at
                ]
            ]);
    }

    public function testReadStateNotFound()
    {
        $this->assertEquals(State::all()->count(), 0);

        $uri = route('state.show', [
            'id' => '0000'
        ]);

        $response = $this->get($uri);

        $response->assertStatus(404);
    }

    public function testShouldUpdateState()
    {
        $this->assertEquals(State::all()->count(), 0);

        $state = factory(State::class)->create();

        $this->assertEquals(State::all()->count(), 1);

        $uri = route('state.update', [
            'id' => $state->id
        ]);

        $data = [
            'state' => [
                'name' => 'Rio de Janeiro',
                'abbreviation' => 'RJ'
            ]
        ];

        $response = $this->put($uri, $data);

        $response->assertStatus(204);

        $this->assertEquals(State::all()->count(), 1);

        $stateUpdated = State::find($state->id);

        $this->assertEquals('Rio de Janeiro', $stateUpdated->name);
        $this->assertNotEquals($state->name, $stateUpdated->name);

        $this->assertEquals('RJ', $stateUpdated->abbreviation);
        $this->assertNotEquals($state->abbreviation, $stateUpdated->abbreviation);
    }

    public function testShouldValidateUpdateStateRequiredAttributes()
    {
        $this->assertEquals(State::all()->count(), 0);

        $location = factory(State::class)->create();

        $this->assertEquals(State::all()->count(), 1);

        $uri = route('state.update', [
            'id' => $location->id
        ]);

        $data = [
            'state' => [
                'name' => null,
                'abbreviation' => null
            ]
        ];

        $response = $this->put($uri, $data);

        $response->assertStatus(422);

        $response->assertJson([
            'state.name' => ['The state.name field must have a value.'],
            'state.abbreviation' => ['The state.abbreviation field must have a value.'],
        ]);

        $this->assertEquals(State::all()->count(), 1);
    }

    public function testUpdateStateNotFound()
    {
        $this->assertEquals(State::all()->count(), 0);

        $uri = route('state.update', [
            'id' => '0000'
        ]);

        $response = $this->put($uri);

        $response->assertStatus(404);
    }

    public function testShouldDeleteStateById()
    {
        $location = factory(State::class)->create();

        $this->assertEquals(State::all()->count(), 1);

        $uri = route('state.destroy', [
            'id' => $location->id
        ]);

        $response = $this->delete($uri);

        $response->assertStatus(204);

        $this->assertEquals(State::all()->count(), 0);
    }

    public function testShouldDeleteStateByIdWithCity()
    {
        $state = factory(State::class)->create();

        $city = factory(City::class)->create([
            'state_id' => $state->id
        ]);

        $this->assertEquals(State::all()->count(), 1);

        $uri = route('state.destroy', [
            'id' => $state->id
        ]);

        $response = $this->delete($uri);

        $response->assertStatus(422);

        $response->assertJson([
            'id' => ['The id is associated with an city.']
        ]);

        $this->assertEquals(State::all()->count(), 1);
    }

    public function testDeleteStateNotFound()
    {
        $this->assertEquals(State::all()->count(), 0);

        $uri = route('state.destroy', [
            'id' => '0000'
        ]);

        $response = $this->delete($uri);

        $response->assertStatus(404);
    }

    public function testListAllStates()
    {
        factory(State::class, 3)->create();

        $this->assertCount(3, State::all());

        $uri = route('state.index');

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    '_id',
                    'name',
                    'abbreviation',
                    'updated_at',
                    'created_at'
                ]
            ]);
    }

    public function testFilterStateByAttributes()
    {
        factory(State::class)->create([
            'name' => 'São Paulo',
            'abbreviation' => 'SP'
        ]);

        factory(State::class)->create([
            'name' => 'Rio Grande do Sul',
            'abbreviation' => 'RS'
        ]);

        factory(State::class)->create([
            'name' => 'Rio Grande do Norte',
            'abbreviation' => 'RN'
        ]);

        $uri = route('state.filter', [
            'name' => 'gran'
        ]);

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(2, 'states');


        $uri = route('state.filter', [
            'abbreviation' => 'SP'
        ]);

        $response = $this->get($uri);

        $response
            ->assertSuccessful()
            ->assertJsonCount(1, 'states');
    }
}