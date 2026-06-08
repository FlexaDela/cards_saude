<?php

namespace Tests\Feature\Admin\Cards;

use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTraitTest extends TestCase
{
    // Cria uma classe "falsa" só para usar a trait
    protected function getTraitObject()
    {
        return new class {
            use ImageUploadTrait;
        };
    }

    public function test_it_returns_empty_array_if_no_images_provided()
    {
        $request = new Request();
        $trait = $this->getTraitObject();

        $result = $trait->imageUpload($request);

        $this->assertEmpty($result);
        $this->assertIsArray($result);
    }

    public function test_it_uploads_valid_images_and_returns_paths()
    {
        // Intercepta o disco public para não salvar lixo no seu PC
        Storage::fake('public');

        $request = new Request();
        $request->merge(['name' => 'Card Mago Negro']);

        $file1 = UploadedFile::fake()->image('frente.jpg');
        $file2 = UploadedFile::fake()->image('verso.png');

        $request->files->set('images', [$file1, $file2]);

        $trait = $this->getTraitObject();
        $result = $trait->imageUpload($request);

        $this->assertCount(2, $result);

        // Verifica se os caminhos foram gerados corretamente com o Str::slug
        $this->assertStringContainsString('cards/card-mago-negro', $result[0]);

        // Garante que o arquivo físico está no disco fake
        Storage::disk('public')->assertExists($result[0]);
        Storage::disk('public')->assertExists($result[1]);
    }
}
