<?php

namespace App\Tests\Controller\Admin\Employee\FrameWork;

use App\Tests\Utility\AuthenticateTestEmployee;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\Test\HasBrowser;

class MainControllerTest extends WebTestCase
{
    use HasBrowser, AuthenticateTestEmployee;

    public function testAdmin()
    {
        // Unauthenticated entry
        $uri = '/admin';
        $this->browser()->visit($uri)->assertNotAuthenticated();

        $browser = $this->browser();
        $client = $browser->client();

        $this->authenticateEmployee($client);

        $browser->visit($uri)
            ->click('a#sidebar-link-category-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-type-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-product-attribute-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-customer-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-web-shop-list')
            ->followRedirects()
            ->assertSuccessful()
            ->visit($uri)
            ->click('a#sidebar-link-settings')
            ->followRedirects()
            ->assertSuccessful();

        //todo: intercept redirects
        // todo: check for country/city/state/postal code

        /*
                $crawler = $this->browser()->visit($uri)->crawler();
                $link = $crawler->selectLink('Categories')->link();

                $client = $this->browser()->client();
                $client->click($link);
                $client->followRedirects(true);
                $this->assertEquals(200, $client->getResponse()->getStatusCode());

                $this->browser()->fillField(
                    'category_create_form[name]', 'Cat1'
                )->fillField('category_create_form[description]', 'Category 1')->fillField(
                        'category_create_form[parent]', ""
                    )->click('Save')->assertSuccessful();
          */
    }
}