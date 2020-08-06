<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateCategoryRequest;
use Illuminate\Http\Request;

/**
 * Class CategoriesController
 * @package App\Http\Controllers
 */
class CategoriesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $total = Category::query()->count();

        if (!empty($request->get('id')) || !empty($request->get('name'))){

            $categories = Category::query();

            if ($request->get('id')) {
                $categories->where('id', $request->get('id'));
            }

            if ($request->get('name')) {
                $categories->where('name', 'like', '%' . $request->get('name') . '%');
            }

            $categories = $categories->paginate(30);
        }
        else
        {
            $categories = Category::all();
        }

        return view('categories.index', compact('categories','request', 'total'));
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
