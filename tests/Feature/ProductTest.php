<?php

declare(strict_types=1);

namespace Tests\Domain\Feature;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private Collection $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory(2)->create();
    }

    /** @test */
    public function itMustReturnProducts(): void
    {
        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment(
                [
                    'id' => $this->product[0]->id,
                    'name' => $this->product[0]->name,
                    'price' => $this->product[0]->price,
                    'description' => $this->product[0]->description,
                    'category' => $this->product[0]->category,
                    'image' => $this->product[0]->image,
                ]
            );
    }

    /** @test */
    public function itMustReturnSpecificProduct(): void
    {
        $response = $this->getJson("/api/v1/products/{$this->product[0]->id}");

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('name', $this->product[0]->name)
            ->where('price', $this->product[0]->price)
            ->where('description', $this->product[0]->description)
            ->where('category', $this->product[0]->category)
            ->where('image', $this->product[0]->image));
    }

    /** @test */
    public function itMustReturnProductsWithContainsQueryStringFilters(): void
    {
        $response = $this->getJson("/api/v1/products?search[name]={$this->product[0]->name}");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(
                [
                    'id' => $this->product[0]->id,
                    'name' => $this->product[0]->name,
                    'price' => $this->product[0]->price,
                    'description' => $this->product[0]->description,
                    'category' => $this->product[0]->category,
                    'image' => $this->product[0]->image,
                ]
            );
    }

    /** @test */
    public function itMustReturnProductsWithSpecificCategory(): void
    {
        $response = $this->getJson("/api/v1/products/categories/{$this->product[1]->category}");

        $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(
            [
                'id' => $this->product[1]->id,
                'name' => $this->product[1]->name,
                'price' => $this->product[1]->price,
                'description' => $this->product[1]->description,
                'category' => $this->product[1]->category,
                'image' => $this->product[1]->image,
            ]
        );
    }

    /** @test */
    public function itMustCreateAProduct(): void
    {
        $response = $this->postJson(
            "/api/v1/products",
            [
                'name' => $this->faker->name(),
                'price' => $this->faker->randomFloat(1, 20, 30),
                'description' => $this->faker->word(),
                'category' => $this->faker->word(),
                'image' => $this->faker->url()
            ]
        );

        $response->assertCreated()
        ->assertJsonCount(1)
        ->assertJson(fn (AssertableJson $json) =>
        $json->has('id'));
    }

    /** @test */
    public function itMustUpdateAProduct(): void
    {
        $response = $this->putJson(
            "/api/v1/products/{$this->product[1]->id}",
            [
                'name' => $this->faker->name(),
                'price' => $this->faker->randomFloat(1, 20, 30),
                'description' => $this->faker->word(),
                'category' => $this->faker->word(),
                'image' => $this->faker->url()
            ]
        );

        $response->assertOk()
        ->assertJsonCount(1)
        ->assertJson(fn (AssertableJson $json) =>
        $json->where('id', $this->product[1]->id));
    }

    /** @test */
    public function itMustDeleteAProduct(): void
    {
        $response = $this->delete("/api/v1/products/{$this->product[1]->id}");

        $response->assertNoContent();

        $responseForProductDeleted = $this->getJson("/api/v1/products/{$this->product[1]->id}");

        $responseForProductDeleted->assertNotFound();
    }

    /** @test */
    public function itShouldNotAcceptToCreateProductWhenNameAlreadyExist()
    {
        $response = $this->postJson(
            "/api/v1/products",
            [
                'name' => $this->product[1]->name,
                'price' => $this->faker->randomFloat(1, 20, 30),
                'description' => $this->faker->word(),
                'category' => $this->faker->word(),
                'image' => $this->faker->url()
            ]
        );

        $response->assertUnprocessable();
    }

     /** @test */
    public function itShouldNotAcceptToCreateProductWhenNotSendRequiredInfos()
    {
        $response = $this->postJson("/api/v1/products", []);

        $response->assertUnprocessable()
        ->assertJsonStructure([
            'errors' => [
                'name',
                'price',
                'description',
                'category'
            ]
            ]);
    }

    /** @test */
    public function itShouldNotAcceptToUpdateProductWhenNotSendRequiredInfos()
    {
        $response = $this->putJson("/api/v1/products/{$this->product[1]->id}", []);

        $response->assertUnprocessable()
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'price',
                    'description',
                    'category'
                ]
                ]);
    }
}
