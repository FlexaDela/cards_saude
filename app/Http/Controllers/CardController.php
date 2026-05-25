<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\Category;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardController extends Controller
{
    use ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $query = Card::with('categories');


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

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('cards.card-create')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Card $card): View
    {
        return view('cards.card-show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card): View
    {
        $categories = Category::all();
        return view('cards.card-edit', compact('categories','card'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $card): RedirectResponse
    {
        $card->update($request->validated());

        $card->categories()->sync($request->categories);

        return to_route('cards.edit', $card->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card): RedirectResponse
    {
        $card->delete();

        return to_route('cards.index');
    }
}
