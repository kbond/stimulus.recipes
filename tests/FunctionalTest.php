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
    public function homepageJson(): void
    {
        $this->browser()
            ->get('/', HttpOptions::json())
            ->assertSuccessful()
            ->json()
                ->assertMatches("contains(recipes, 'tabs')", true)
                ->assertMatches("contains(recipes, 'datepicker')", true)
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
            ->assertSee('Tabs allow showing and hiding content the currently active tab.')
            ->assertSee('https://railsnotes.xyz/blog/simple-stimulus-tabs-controller')
        ;
    }

    /**
     * @test
     */
    public function viewRecipeJson(): void
    {
        $this->browser()
            ->get('/tabs.json')
            ->assertSuccessful()
            ->assertJson()
            ->get('/tabs', HttpOptions::json())
            ->assertSuccessful()
            ->json()
                ->assertMatches('name', 'tabs')
                ->assertMatches('title', 'Tabs')
                ->assertMatches('description', 'Tabs allow showing and hiding content the currently active tab.')
                ->assertMatches('dependencies.php', ['symfony/ux-twig-component'])
                ->assertMatches('references', ['https://railsnotes.xyz/blog/simple-stimulus-tabs-controller', 'https://flowbite.com/docs/components/tabs/'])
                ->assertMatches('files[0].path', 'assets/controllers/tabs_controller.js')
                ->assertHas('files[0].source')
        ;

        $this->browser()
            ->get('/datepicker.json')
            ->assertSuccessful()
            ->json()
                ->assertMatches('dependencies.js', ['flowbite-datepicker'])
        ;
    }
}
