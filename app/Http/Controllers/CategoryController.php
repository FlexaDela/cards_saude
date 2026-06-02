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
    public function index(Request $request): View
    {
        $messageSuccess = $request->session()->get('message.success');
        $messageError = $request->session()->get('message.error');
        $categories = Category::all();

        return view('cards.category-index', compact('categories','messageSuccess','messageError'));
    }
    public function create(Request $request): View
    {
        return view('cards.category-create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {

        Category::create($request->validated());

        return to_route('cards.index');
    }

    public function edit(Category $category, Request $request): View
    {
        return view('cards.category-edit')->with('category',$category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('cards.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if($category->cards()->exists()){
            return back()->with('message.error','Esta categoria ainda possue cards dentro dela');
        }

        $category->delete();

        return to_route('cards.index')->with('message.success',"Categoria: '$category->name' deletada com sucesso");
    }
}
