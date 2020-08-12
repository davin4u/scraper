<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;


class DomainsController extends Controller
{
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

    public function domainsIndex()
    {
        $domains = Domain::query()->paginate(30);

        return view('domains.domains_index')->with([
            'domains' => $domains
        ]);
    }

    public function domainsCreate()
    {
        return view('domains.domains_create');
    }

    public function domainsStore()
    {
        Domain::create([
            'name' => $this->request->get('name'),
            'url' => $this->request->get('url')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Domain has been created']);
    }

    public function domainsEdit(Domain $domain)
    {
        return view('domains.domains_edit', compact('domain'));
    }

    public function domainsUpdate(Domain $domain)
    {
        $domain->update([
            'name' => $this->request->get('name'),
            'url' => $this->request->get('url')
        ]);

        return redirect(route('domains.index'))->with(['status' => 'Domain has been updated']);
    }

    public function domainsDestroy(Domain $domain)
    {
        $domain->delete();

        return redirect(route('domains.index'))->with(['status' => 'Domain has been deleted']);
    }

    //Stores

    public function storesCreate(Domain $domain)
    {
        return view('domains.stores_create', compact('domain'));
    }

    //StoreLocations
}
