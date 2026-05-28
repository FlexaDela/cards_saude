<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    use ImageUploadTrait;


    public function index(Request $request): View
    {

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

        return view('cards', compact('cards', 'categories'));

    }


    public function create(): View
    {
        $categories = Category::all();
        return view('cards.card-create')->with('categories',$categories);
    }


    public function store(StoreCardRequest $request): RedirectResponse
    {
        $card = Card::create($request->validated());
        $card->categories()->sync($request->categories);

        $imagesPaths = $this->imageUpload($request);

        foreach($imagesPaths as $key => $path){
            $card->cardImages()->create([
                'path'=> $path,
                'principal' => ($key === 0)
            ]);
        }

        return to_route('cards.index')->with('sucess', 'Card criado com sucesso');
    }


    public function show(Card $card): View
    {
        return view('cards.card-show');
    }


    public function edit(Card $card): View
    {
        $categories = Category::all();
        return view('cards.card-edit', compact('categories','card'));
    }


    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $card->update($request->validated());

        $card->categories()->sync($request->categories);

        return to_route('cards.edit', $card->id);
    }

    public function destroy(Card $card): RedirectResponse
    {
        if($card->show === false){

            DB::transaction(function () use ($card) {
                //Deleta o diretorio do card informado
                Storage::disk('public')->deleteDirectory('cards/' . Str::slug($card->name));

                //deletar card
                $card->delete();
            });

            return to_route('cards.index')->with('message.success','Card deletado com sucesso');
        }

        return back('cards.edit', $card->id)->with('message.error','Este card está amostra na vitrine!');
    }
}
