<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Domain;
use App\Http\Requests\DomainStoreUpdateRequest;
use App\Http\Requests\StoreLocationStoreUpdateRequest;
use App\Http\Requests\StoreStoreUpdateRequest;
use App\Store;
use App\StoreLocation;
use Illuminate\Http\Request;


/**
 * Class DomainsController
 * @package App\Http\Controllers
 */
class DomainsController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * DomainsController constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    //Domains

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domainsIndex()
    {
        $domains = Domain::query()->paginate(30);

        return view('domains.domains_index')->with([
            'domains' => $domains
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domainsCreate()
    {
        return view('domains.domains_create');
    }

    /**
     * @param DomainStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function domainsStore(DomainStoreUpdateRequest $request)
    {
        Domain::create([
            'name' => $request->get('name'),
            'url' => $request->get('url')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Domain has been created']);
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domainsEdit(Domain $domain)
    {
        $stores = $domain->stores()->get();

        return view('domains.domains_edit', compact('domain', 'stores'));
    }

    /**
     * @param Domain $domain
     * @param DomainStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function domainsUpdate(Domain $domain, DomainStoreUpdateRequest $request)
    {
        $domain->update([
            'name' => $request->get('name'),
            'url' => $request->get('url')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Domain has been updated']);
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function domainsDestroy(Domain $domain)
    {
        $domain->delete();

        return redirect(route('domains.index'))->with(['status' => 'Domain has been deleted']);
    }

    //Stores

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storesCreate(Domain $domain)
    {
        $countries = Country::all();
        $cities = City::all();

        return view('domains.stores_create', compact('domain', 'countries', 'cities'));
    }

    /**
     * @param Domain $domain
     * @param StoreStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storesStore(Domain $domain, StoreStoreUpdateRequest $request)
    {
        Store::create([
            'country_id' => $request->get('country_id'),
            'city_id' => $request->get('city_id'),
            'domain_id' => $domain->id
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Store has been created']);
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storesEdit(Domain $domain, Store $store)
    {
        $countries = Country::all();
        $cities = City::all();

        return view('domains.stores_edit', compact('domain', 'store', 'countries', 'cities'));
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @param StoreStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storesUpdate(Store $store, StoreStoreUpdateRequest $request)
    {
        $store->update([
            'country_id' => $request->get('country_id'),
            'city_id' => $request->get('city_id')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Store has been updated']);
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function storesDestroy(Store $store)
    {
        $store->delete();

        return redirect(route('domains.index'))->with(['status' => 'Store has been deleted']);
    }

    //StoreLocations

    /**
     * @param Domain $domain
     * @param Store $store
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeLocationsCreate(Domain $domain, Store $store)
    {
        return view('domains.store_locations_create', compact('domain', 'store'));
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @param StoreLocationStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeLocationsStore(Store $store, StoreLocationStoreUpdateRequest $request)
    {
        StoreLocation::create([
            'store_id' => $store->id,
            'location_name' => $request->get('location_name'),
            'address' => $request->get('address'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'phone' => $request->get('phone'),
            'location_description' => $request->get('location_description')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Store Location has been created']);
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @param StoreLocation $storeLocation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeLocationsEdit(Domain $domain, Store $store, StoreLocation $storeLocation)
    {
        return view('domains.store_locations_edit', compact('domain', 'store', 'storeLocation'));
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @param StoreLocation $storeLocation
     * @param StoreLocationStoreUpdateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeLocationsUpdate(StoreLocation $storeLocation, StoreLocationStoreUpdateRequest $request)
    {
        $storeLocation->update([
            'location_name' => $request->get('location_name'),
            'address' => $request->get('address'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'phone' => $request->get('phone'),
            'location_description' => $request->get('location_description')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Store Location has been updated']);
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @param StoreLocation $storeLocation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function storeLocationsDestroy(StoreLocation $storeLocation)
    {
        $storeLocation->delete();

        return redirect(route('domains.index'))->with(['status' => 'Store Location has been deleted']);
    }
}
