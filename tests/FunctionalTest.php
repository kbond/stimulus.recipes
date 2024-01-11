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
}
