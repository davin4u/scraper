<?php

namespace App\Http\Controllers;

use App\Category;
use App\Domain;
use App\Http\Requests\YmlDataExtractUploadRequest;
use App\Http\Requests\YmlDataImportUploadRequest;
use App\Store;
use App\StoreProduct;
use App\StoreProductDetails;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Sirian\YMLParser\Parser;

/**
 * Class YmlDataImportController
 * @package App\Http\Controllers
 */
class YmlDataImportController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * YmlDataImportController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('yml_import.index');
    }

    /**
     * @param YmlDataImportUploadRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload(YmlDataImportUploadRequest $request)
    {
        $ymlFilePath = $request->file('yml-file')->getPathname();
        Storage::putFileAs('import_files', new File($ymlFilePath), 'import_yml.xml');

        $parser = new Parser();
        $data = $parser->parse($ymlFilePath);
        $isDomainExists = Domain::query()->where('name', $data->getShop()->getName())->first() ? 'yes' : 'no';
        $categoriesFromDB = Category::query()->pluck('name')->toArray();
        $existsCategories = [];

        foreach ($data->getShop()->getCategories() as $category) {
            if (in_array($category->getName(), $categoriesFromDB)) {
                $existsCategories[$category->getId()] = 'yes';
            } else $existsCategories[$category->getId()] = 'no';
        }

        return view('yml_import.show')->with([
            'categories' => $data->getShop()->getCategories(),
            'total' => $data->getShop()->getOffersCount(),
            'isDomainExists' => $isDomainExists,
            'existsCategories' => $existsCategories,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function import()
    {
        $ymlFilePath = Storage::path('import_files/import_yml.xml');
        $parser = new Parser();
        $data = $parser->parse($ymlFilePath);
        $categoriesFromDB = Category::query()->pluck('name')->toArray();
        $selectedCategories = $this->request->get('selectedCategories');
        $categories = [];
        $isDomainExists = Domain::query()->where('name', $data->getShop()->getName())->first();

        if (is_null($isDomainExists)) {
            Domain::create([
                'name' => $data->getShop()->getName(),
                'url' => $data->getShop()->getUrl()
            ]);

            Store::create([
                'country_id' => 0,
                'city_id' => 0,
                'domain_id' => Domain::query()->where('name', $data->getShop()->getName())->value('id')
            ]);
        }

        $domainId = Domain::query()->where('name', $data->getShop()->getName())->value('id');
        $storeId = Store::query()->whereHas('domain', function ($query) use ($domainId) {
            $query->where('id', $domainId);
        })->value('id');

        if (!is_null($selectedCategories)) {
            foreach ($data->getShop()->getCategories() as $category) {
                if (in_array($category->getId(), $selectedCategories)) {
                    $categories[] = $category->getName();
                }
            }
        }

        $uniqueOfferIds = [];
        $duplicatedOffers = [];

        foreach ($data->getOffers() as $offer) {
            if (in_array($offer->getCategory()->getName(), $categories)) {

                if (!in_array($offer->getId(), $uniqueOfferIds)) {

                    $getOfferById = StoreProduct::query()->where('yml_id', $offer->getId())->value('yml_id');

                    if (is_null($getOfferById)) {

                        StoreProduct::create([
                            'store_id' => $storeId,
                            'product_id' => 0,
                            'yml_id' => $offer->getId()
                        ]);

                        $storeProductId = StoreProduct::query()->where('yml_id', $offer->getId())->value('id');

                        StoreProductDetails::create([
                            'store_product_id' => $storeProductId,
                            'name' => $offer->getName(),
                            'url' => trim(explode('?', $offer->getUrl())[0]),
                            'price' => $offer->getPrice(),
                            'old_price' => $offer->getOldPrice(),
                            'currency' => $offer->getCurrency()->getId(),
                            'is_available' => $offer->isAvailable()
                        ]);

                    } else {
                        $storeProductDetailsId = StoreProductDetails::query()->whereHas('storeProduct', function ($query) use ($getOfferById) {
                            $query->where('yml_id', $getOfferById);
                        })->value('id');

                        StoreProductDetails::query()->where('id', $storeProductDetailsId)->update([
                            'name' => $offer->getName(),
                            'url' => trim(explode('?', $offer->getUrl())[0]),
                            'price' => $offer->getPrice(),
                            'old_price' => $offer->getOldPrice(),
                            'currency' => $offer->getCurrency()->getId(),
                            'is_available' => $offer->isAvailable()
                        ]);
                    }
                }

                if (in_array($offer->getId(), $uniqueOfferIds)) {
                    $duplicatedOffers[] = [
                        'id' => $offer->getId(),
                        'name' => $offer->getName(),
                        'category' => $offer->getCategory()->getName()
                    ];
                }

                $uniqueOfferIds[] = $offer->getId();

            }
        }

        foreach ($categories as $category) {
            if (!in_array($category, $categoriesFromDB)) {
                $map = explode(',', $category) ?? [];
                Category::create([
                    'name' => $category,
                    'map' => $map
                ]);
            }
        }

        Storage::delete('import_files/import_yml.xml');

        return redirect(route('yml-import.index'))->with(['status' => 'Data has been imported', 'duplicatedOffers' => $duplicatedOffers]);
    }
}
