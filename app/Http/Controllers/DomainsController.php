<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Requests\CreateDomainRequest;

/**
 * Class DomainsController
 * @package App\Http\Controllers
 */
class DomainsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $domains = Domain::all();

        return view('domains.index', compact('domains'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('domains.create');
    }

    /**
     * @param CreateDomainRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateDomainRequest $request)
    {
        Domain::create($request->validated());

        return redirect(route('domains.index'))->with(['status' => 'Domain has been created.']);
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Domain $domain)
    {
        return view('domains.edit', compact('domain'));
    }

    /**
     * @param CreateDomainRequest $request
     * @param Domain $domain
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(CreateDomainRequest $request, Domain $domain)
    {
        $domain->update($request->validated());

        return redirect(route('domains.index'))->with(['status' => 'Domain has been updated.']);
    }

    /**
     * @param Domain $domain
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect(route('domains.index'))->with(['status' => 'Domain has been deleted.']);
    }
}
