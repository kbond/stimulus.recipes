<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
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
    public function recipeRedirect(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/recipe')
            ->assertRedirectedTo('/')
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
            ->assertOn('/recipe/tabs')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function viewRecipe(): void
    {
        $this->browser()
            ->visit('/recipe/tabs')
            ->assertSuccessful()
            ->assertSeeIn('title', 'Tabs')
            ->assertElementAttributeContains('meta[name=description]', 'content', 'Tabs allow showing and hiding content the currently active tab.')
            ->assertSeeIn('h1', 'Tabs')
            ->assertSeeIn('p', 'Tabs allow showing and hiding content the currently active tab.')
            ->assertSeeIn('h2', 'Credit')
            ->assertSeeIn('ul li', 'https://railsnotes.xyz/blog/simple-stimulus-tabs-controller')
        ;
    }
}
