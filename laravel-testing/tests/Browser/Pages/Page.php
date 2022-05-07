<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;
use Laravel\Dusk\Browser;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@element' => '#selector',
        ];
    }
    public function login(Browser $browser, $username, $password)
    {
        $browser->type('username', $username)
                ->type('password', $password)
                ->press('Create Playlist');
    }
}
