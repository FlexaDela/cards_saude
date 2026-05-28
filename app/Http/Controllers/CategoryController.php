<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function create(Request $request): View
    {

        return view('cards.category-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {

        Category::create($request->validated());

        return to_route('cards.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, Request $request): View
    {
        return view('cards.category-edit')->with('category',$category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('cards.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if($category->card()->exist()){
            return back('cards.index')->with('message.error','Esta categoria ainda possue cards dentro dela');
        }

        $category->delete();

        return to_route('cards.index')->with('message.success',"Categoria: '$category->name' deletada com sucesso");
    }
}
