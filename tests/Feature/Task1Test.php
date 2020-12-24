<?php

namespace Tests\Feature;

use App\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class Task1Test extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    protected $modelFields = [
        "first_name",
        "last_name",
        "middle_name"
    ];
    protected $modelClass = Person::class;
    protected $modelPluralName = "persons";
    protected $modelSingleName = "person";


    /* Checks model saving */
    public function testStoreOk()
    {
        $data = factory($this->modelClass)->make()->toArray();
        $routeName = $this->modelPluralName . ".store";
        $redirectRouteName = $this->modelPluralName . ".show";
        $response = $this->post(route($routeName), $data);
        $response->assertRedirect(route($redirectRouteName, [$this->modelSingleName => 1]));
    }

    /* Checks saving validation */
    public function testStoreError()
    {
        $routeName = $this->modelPluralName . ".store";
        $response = $this->post(route($routeName), []);
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors($this->modelFields);
    }

    /* Checks json model updating */
    public function testUpdateOk()
    {
        $model = factory($this->modelClass)->create();
        $data = factory($this->modelClass)->make()->toArray();
        $routeName = $this->modelPluralName . ".update";
        $response = $this->putJson(route($routeName, [$this->modelSingleName => $model->id]), $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => $this->modelFields]);
        $response->assertJsonFragment($data);

    }
    /* Checks json model updating validation */
    public function testUpdateError()
    {
        $model = factory($this->modelClass)->create();
        $routeName = $this->modelPluralName . ".update";
        $response = $this->putJson(route($routeName, [$this->modelSingleName => $model->id]), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors'=>$this->modelFields]);
    }
 

}