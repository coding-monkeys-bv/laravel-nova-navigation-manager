<?php

namespace Voicecode\NavigationManager\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Voicecode\NavigationManager\Models\Navigation;
use Voicecode\NavigationManager\Models\NavigationItem;
use Voicecode\NavigationManager\Http\Controllers\Traits\NavigationItemsTrait;

class NavigationController extends Controller
{
    use NavigationItemsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Navigation::get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Clear cache
        $this->clearCache($id);

        // Cache the navigation JSON for 15 minutes.
        $parents = Cache::remember('navigation_'.$id, 15, function () use ($id) {
            
            // Get all navigation items with child items.
            $items = NavigationItem::where('navigation_id', $id)
                    ->where('parent_id', null)
                    ->orderBy('order')
                    ->with(['children' => function ($query) {
                            $query->orderBy('order');
                    }])
                    ->get();

            return $items;
        });
        
        foreach ($parents as $parent) {
            $parent->editable = false;
        }

        return response()->json($parents);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Flush navigation caches when asked for.
     *
     * @param int   $navigationId
     *
     * @return void
     */
    public function clearCache($navigationId)
    {
        Cache::forget('navigation_'.$navigationId);
    }
}
