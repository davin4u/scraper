<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateCategoryRequest;

/**
 * Class CategoriesController
 * @package App\Http\Controllers
 */
class CategoriesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * @param CreateCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateCategoryRequest $request)
    {
        $name = $request->get('name');
        $map  = $request->get('map') ? explode(',', $request->get('map')) : [];

        $map = array_map(function ($item) {
            return trim($item);
        }, $map);

        $category = Category::create([
            'name' => $name,
            'map'  => $map
        ]);

        return redirect(route('categories.index'))->with(['status' => 'Category has been created.']);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * @param Category $category
     * @param CreateCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Category $category, CreateCategoryRequest $request)
    {
        $name = $request->get('name');
        $map  = $request->get('map') ? explode(',', $request->get('map')) : [];

        $map = array_map(function ($item) {
            return trim($item);
        }, $map);

        $category->update([
            'name' => $name,
            'map'  => $map
        ]);

        return redirect(route('categories.index'))->with(['status' => 'Category has been updated.']);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect(route('categories.index'))->with(['status' => 'Category has been deleted.']);
    }
}
