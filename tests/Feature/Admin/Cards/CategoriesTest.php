<?php

namespace Tests\Feature\Admin\Cards;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
    */
    use RefreshDatabase;

    public function test_create_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->get('painel-dona-bebeth/cards/categories/create');

        $response
        ->assertOk();
    }

    public function test_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->post('painel-dona-bebeth/cards/categories',
        [
            'name' => 'test',
            'description' => 'Mussum Ipsum, cacilds vidis litro abertis. Delegadis gente finis, bibendum egestas augue arcu ut est.'
        ]);

        $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('categories.index', absolute:false));
    }

    public function test_create_category_regex(): void
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->post('painel-dona-bebeth/cards/categories',
        [
            'name' => 'test. ',
            'description' => 'Mussum Ipsum, cacilds vidis litro abertis. Delegadis gente finis, bibendum egestas augue arcu ut est.'
        ]);

        $response
        ->assertSessionHasErrors(['name'])
        ->assertStatus(302);
    }

    public function test_edit_category(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $category = Category::factory()->create([
            'name' => 'old name',
            'description' => 'old description'
        ]);

        $response = $this
        ->actingAs($user)
        ->put("painel-dona-bebeth/cards/categories/{$category->id}",
        [
            'name' => "another name",
            'description' => "another description"
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => "another name",
            'description' => "another description"
        ]);
    }
}
