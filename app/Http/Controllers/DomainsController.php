<?php

namespace App\Http\Controllers;

use App\Country;
use App\Domain;
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function domainsStore()
    {
        Domain::create([
            'name' => $this->request->get('name'),
            'url' => $this->request->get('url')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Domain has been created']);
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function domainsEdit(Domain $domain)
    {
        return view('domains.domains_edit', compact('domain'));
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function domainsUpdate(Domain $domain)
    {
        $domain->update([
            'name' => $this->request->get('name'),
            'url' => $this->request->get('url')
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
        return view('domains.stores_create', compact('domain'));
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storesStore(Domain $domain)
    {
        Store::create([
            'country_id' => $this->request->get('country_id'),
            'city_id' => $this->request->get('city_id'),
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
        return view('domains.stores_edit', compact('domain', 'store'));
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storesUpdate(Domain $domain, Store $store)
    {
        $store->update([
            'country_id' => $this->request->get('country_id'),
            'city_id' => $this->request->get('city_id')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Store has been updated']);
    }

    /**
     * @param Domain $domain
     * @param Store $store
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function storesDestroy(Domain $domain, Store $store)
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeLocationsStore(Domain $domain, Store $store)
    {
        StoreLocation::create([
            'store_id' => $store->id,
            'location_name' => $this->request->get('location_name'),
            'address' => $this->request->get('address'),
            'latitude' => $this->request->get('latitude'),
            'longitude' => $this->request->get('longitude'),
            'phone' => $this->request->get('phone'),
            'location_description' => $this->request->get('location_description')
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeLocationsUpdate(Domain $domain, Store $store, StoreLocation $storeLocation)
    {
        $storeLocation->update([
            'location_name' => $this->request->get('location_name'),
            'address' => $this->request->get('address'),
            'latitude' => $this->request->get('latitude'),
            'longitude' => $this->request->get('longitude'),
            'phone' => $this->request->get('phone'),
            'location_description' => $this->request->get('location_description')
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
    public function storeLocationsDestroy(Domain $domain, Store $store, StoreLocation $storeLocation)
    {
        $storeLocation->delete();

        return redirect(route('domains.index'))->with(['status' => 'Store Location has been deleted']);
    }
}
