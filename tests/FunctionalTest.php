<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\Test\HasBrowser;

class FunctionalTest extends KernelTestCase
{
    use HasBrowser;

    /**
     * @test
     */
    public function homepage(): void
    {
        $this->browser()
            ->visit('/')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function gettingStarted(): void
    {
        $this->browser()
            ->visit('/')
            ->click('Getting Started')
            ->assertOn('/getting-started')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function browseRecipes(): void
    {
        $this->browser()
            ->visit('/')
            ->click('Tabs')
            ->assertOn('/tabs')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function viewRecipe(): void
    {
        $this->browser()
            ->visit('/tabs')
            ->assertSuccessful()
            ->assertSeeIn('title', 'Tabs')
            ->assertElementAttributeContains('meta[name=description]', 'content', 'Tabs allow showing and hiding content the currently active tab.')
            ->assertSeeIn('h1', 'Tabs')
            ->assertSeeIn('p', 'Tabs allow showing and hiding content the currently active tab.')
            ->assertSeeIn('ul li', 'https://railsnotes.xyz/blog/simple-stimulus-tabs-controller')
        ;
    }

    /**
     * @test
     */
    public function viewRecipeJson(): void
    {
        $this->browser()
            ->get('/tabs.json')
            ->assertJson()
            ->get('/tabs', HttpOptions::json())
            ->assertJson()
            ->json()
                ->assertMatches('name', 'tabs')
                ->assertMatches('title', 'Tabs')
                ->assertMatches('description', 'Tabs allow showing and hiding content the currently active tab.')
                ->assertMatches('credit', ['https://railsnotes.xyz/blog/simple-stimulus-tabs-controller'])
                ->assertMatches('controller.name', 'tabs_controller.js')
                ->assertHas('controller.source')
        ;

        $this->browser()
            ->get('/datepicker.json')
            ->json()
                ->assertMatches('js_dependencies', ['flowbite-datepicker'])
        ;
    }
}
