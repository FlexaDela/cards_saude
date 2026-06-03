<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\CardImage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CardController extends Controller
{
    use ImageUploadTrait;


    public function index(Request $request): View
    {
        $messageSuccess = $request->session()->get('message.success');

        $query = Card::with(['categories','coverImage']);


        $query->when($request->disponivel, function ($q) {
            return $q->where('available', true);
        });


        $query->when($request->visivel, function ($q) {
            return $q->where('show', true);
        });


        $query->when($request->category_id, function ($q) use ($request) {
            return $q->whereHas('categories', function ($subQuery) use ($request) {
                $subQuery->where('categories.id', $request->category_id);
            });
        });

        $cards = $query->get();
        $categories = Category::all();

        return view('cards', compact('cards', 'categories','messageSuccess'));

    }


    public function create(): View
    {
        $categories = Category::all();
        return view('cards.card-create')->with('categories',$categories);
    }


    public function store(StoreCardRequest $request): RedirectResponse
    {
        try{

            DB::transaction(function() use($request) {

                $card = Card::create($request->validated());
                $card->categories()->sync($request->categories);

                $imagesPaths = $this->imageUpload($request);

                foreach($imagesPaths as $key => $path){
                    $card->cardImages()->create([
                        'path'=> $path,
                        'principal' => ($key === 0)
                    ]);
                }
            });

            $request->session()->flash('message.success','Card criado com sucesso!');

            return to_route('cards.index');

        } catch(Throwable $e) {

            Log::error('Erro ao criar o card:' . $e->getMessage());

            $request->session()->flash('message.error','Erro ao criar o card');

            return back();
        }
    }


    public function show(Card $card): View
    {
        return view('cards.card-show');
    }


    public function edit(Card $card, Request $request): View
    {
        $categories = Category::all();
        $messageSuccess = $request->session()->get('message.success');
        $messageError = $request->session()->get('message.error');

        return view('cards.card-edit', compact('categories','card','messageSuccess','messageError'));
    }


    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {

        DB::transaction(function() use($card, $request) {

            $card->update($request->validated());
            $card->categories()->sync($request->categories);

            $imagesPaths = $this->imageUpload($request);

            foreach($imagesPaths as $path){
                $card->cardImages()->create([
                    'path' => $path,
                    'principal' => false,
                ]);
            }
        });

        $request->session()->flash('message.success','Card atualizado com sucesso');

        return back();
    }

    public function destroy(Card $card, Request $request): RedirectResponse
    {
        if($card->show === false){

            DB::transaction(function () use ($card) {

                Storage::disk('public')->deleteDirectory('cards/' . Str::slug($card->name));

                $card->delete();
            });

            $request->session()->flash('message.success','Card deletado com sucesso');

            return to_route('cards.index');
        }

        $request->session()->flash('message.error','Este card está na vitrine!');

        return back();
    }

    public function destroyImage(Request $request ,CardImage $cardImage): RedirectResponse
    {
        if($cardImage->principal === 0){

            DB::transaction(function() use($cardImage) {

            Storage::disk('public')->delete($cardImage->path);
            $cardImage->delete();

            });

            $request->session()->flash('message.success','Imagem deletada com sucesso');

            return back();
        }

        $request->session()->flash('message.error','Esta imagem é principal');

        return back();
    }

    public function makeCoverImage(Card $card, CardImage $cardImage): RedirectResponse
    {
        return back();
    }
}
