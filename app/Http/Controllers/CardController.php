<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::all();
        $cards = Card::with('categories')->get();



        return view('cards')->with('categories', $categories)->with('cards',$cards);
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

        return to_route('cards.index');
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
        return view('cards.card-edit')->with('card',$card)->with('categories',$categories);
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
