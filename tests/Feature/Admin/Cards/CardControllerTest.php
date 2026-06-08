<?php

namespace Tests\Feature\Admin\Cards;

use App\Models\Card;
use App\Models\CardImage;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase; //Reseta o baanco de dados a cada teste

    public function test_store_creates_card_and_uploads_images_successfully()
    {
        Storage::fake('public'); //Qual salvamento no disco será temporario
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $response = $this
        ->actingAs($user)
        ->post(route('cards.store'),[
            'name' => 'nurse card',
            'available' => true,
            'show' => true,
            'price' => 0.15,
            'categories' => [$category->id],
            'images' => [
                UploadedFile::fake()->image('dragao.jpg')
            ]
        ]);

        $response->assertRedirect(route('cards.index'));
        $response->assertSessionHas('message.success');
        $this->assertDatabaseHas('cards', ['name' => 'nurse card']);

        $card = Card::where('name', 'nurse card')->first();
        Storage::disk('public')->assertExists($card->cardImages()->first()->path);
    }

    public function teste_request_form_redirect_error()
    {
        $user = User::factory()->create();
        Storage::fake('public');
        $response = $this
        ->actingAs($user)
        ->post(route('cards.store'),[
            'name' => 'nurse card',
            'available' => true,
            'show' => true,
            'price' => 0.15,
            'categories' => ['categoria'],
            'images' => [
                UploadedFile::fake()->image('dragao.jpg')
            ]
        ]);

        $response->assertStatus(302);
        //$response->assertSessionHas('message.error');
        $this->assertDatabaseMissing('cards', ['name' => 'Card Que Vai Falhar']);

        // A prova de fogo: o catch do Controller tem que ter apagado o arquivo!
        $files = Storage::disk('public')->files('cards/card-que-vai-falhar');
        $this->assertEmpty($files, 'O arquivo deveria ter sido deletado pelo catch!');
    }

    public function test_destroy_deletes_card_and_directory_if_not_in_vitrine()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();
        $response = $this
        ->actingAs($user)
        ->post(route('cards.store'),[
            'name' => 'nurse card',
            'available' => true,
            'show' => false,
            'price' => 0.15,
            'categories' => [$category->id],
            'images' => [
                UploadedFile::fake()->image('dragao.jpg')
            ]
        ]);

        $card = Card::where('name','nurse card')->first();

        $folderPath = 'cards/' . Str::slug($card->name);
        UploadedFile::fake()->image('foto.jpg')->storeAs($folderPath, 'foto.jpg', 'public');

        $response_delete = $this->actingAs($user)->delete(route('cards.destroy', $card->id));

        $response_delete->assertRedirect(route('cards.index'));
        $response_delete->assertSessionHas('message.success');

        $this->assertDatabaseMissing('cards', ['id' => $card->id]);

        // O diretório deve ter sido apagado após o delete() do banco
        $this->assertEmpty(Storage::disk('public')->files($folderPath));
    }

    public function test_destroy_prevents_deletion_if_card_is_in_vitrine()
    {

        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();
        $response = $this
        ->actingAs($user)
        ->post(route('cards.store'),[
            'name' => 'nurse card',
            'available' => true,
            'show' => true,
            'price' => 0.15,
            'categories' => [$category->id],
            'images' => [
                UploadedFile::fake()->image('dragao.jpg')
            ]
        ]);

        $card = Card::where('name','nurse card')->first();

        $response_delete = $this->delete(route('cards.destroy', $card));

        $response_delete->assertStatus(302);
        $response_delete->assertSessionHas('message.error', 'Este card está na vitrine!');

        // Garante que o card continua no banco
        $this->assertDatabaseHas('cards', ['id' => $card->id]);
    }

    public function test_destroy_image_deletes_record_and_file_if_not_principal()
    {
        Storage::fake('public');

        $cardImage = CardImage::factory()->create([
            'path' => 'cards/teste/imagem.jpg',
            'principal' => false
        ]);

        // Coloca o arquivo no disco fake
        UploadedFile::fake()->image('imagem.jpg')->storeAs('cards/teste', 'imagem.jpg', 'public');

        $response = $this->delete(route('cards.destroyImage', $cardImage));

        $response->assertStatus(302);
        $response->assertSessionHas('message.success');

        $this->assertDatabaseMissing('card_images', ['id' => $cardImage->id]);
        Storage::disk('public')->assertMissing('cards/teste/imagem.jpg');
    }

    public function test_destroy_image_prevents_deletion_if_is_principal()
    {
        $cardImage = CardImage::factory()->create([
            'principal' => true // Impede deletar
        ]);

        $response = $this->delete(route('cards.destroyImage', $cardImage));

        $response->assertStatus(302);
        $response->assertSessionHas('message.error', 'Esta imagem é principal');

        $this->assertDatabaseHas('card_images', ['id' => $cardImage->id]);
    }
}
