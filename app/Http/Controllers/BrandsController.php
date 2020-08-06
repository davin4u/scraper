<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Http\Requests\CreateBrandRequest;
use Illuminate\Http\Request;

/**
 * Class BrandsController
 * @package App\Http\Controllers
 */
class BrandsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $total = Brand::query()->count();

        if (!empty($request->get('id')) || !empty($request->get('name'))){

            $brands = Brand::query();

            if ($request->get('id')) {
                $brands->where('id', $request->get('id'));
            }

            if ($request->get('name')) {
                $brands->where('name', 'like', '%' . $request->get('name') . '%');
            }

            $brands = $brands->paginate(30);
        }
        else
        {
            $brands = Brand::all();
        }

        return view('brands.index', compact('brands','request', 'total'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * @param CreateBrandRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateBrandRequest $request)
    {
        $name = $request->get('name');
        $map  = $request->get('map') ? explode(',', $request->get('map')) : [];

        $map = array_map(function ($item) {
            return trim($item);
        }, $map);

        $brand = Brand::create([
            'name' => $name,
            'map'  => $map
        ]);

        return redirect(route('brands.index'))->with(['status' => 'Brand has been created.']);
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    /**
     * @param Brand $brand
     * @param CreateBrandRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Brand $brand, CreateBrandRequest $request)
    {
        $name = $request->get('name');
        $map  = $request->get('map') ? explode(',', $request->get('map')) : [];

        $map = array_map(function ($item) {
            return trim($item);
        }, $map);

        $brand->update([
            'name' => $name,
            'map'  => $map
        ]);

        return redirect(route('brands.index'))->with(['status' => 'Brand has been updated.']);
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect(route('brands.index'))->with(['status' => 'Brand has been deleted']);
    }
}
