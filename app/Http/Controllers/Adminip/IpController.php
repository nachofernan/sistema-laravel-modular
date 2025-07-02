<?php

namespace App\Http\Controllers\Adminip;

use App\Http\Controllers\Controller;
use App\Models\Adminip\IP;
use Illuminate\Http\Request;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('adminip.ips.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ip $ip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ip $ip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ip $ip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ip $ip)
    {
        //
    }
}
