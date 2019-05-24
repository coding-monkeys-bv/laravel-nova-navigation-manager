<?php

namespace Voicecode\NavigationManager;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NavigationManager extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('navigation-manager', __DIR__.'/../dist/js/tool.js');
        Nova::style('navigation-manager', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('navigation-manager::navigation');
    }
}
