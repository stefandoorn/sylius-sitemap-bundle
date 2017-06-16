<?php

namespace Tests\SitemapPlugin\Controller;

use Lakion\ApiTestCase\MediaTypes;
use Lakion\ApiTestCase\XmlApiTestCase;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\Taxon;

/**
 * @author Stefan Doorn <stefan@efectos.nl>
 */
class SitemapIndexControllerApiTest extends XmlApiTestCase
{
    /**
     * @before
     */
    public function setUpDatabase()
    {
        parent::setUpDatabase();

        $product = new Product();
        $product->setCurrentLocale('en_US');
        $product->setName('Test');
        $product->setCode('test-code');
        $product->setSlug('test');
        $this->getEntityManager()->persist($product);

        $taxon = new Taxon();
        $taxon->setCurrentLocale('en_US');
        $taxon->setName('Mock');
        $taxon->setCode('mock-code');
        $taxon->setSlug('mock');
        $this->getEntityManager()->persist($taxon);

        $this->getEntityManager()->flush();
    }

    public function testShowActionResponse()
    {
        $this->client->request('GET', '/sitemap_index.xml');

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'show_sitemap_index');
    }

    public function testShowActionResponseRelative()
    {
        $this->client = static::createClient(array('environment' => 'testing_relative'), array('HTTP_ACCEPT' => MediaTypes::XML));
        $this->client->request('GET', '/sitemap_index.xml');

        $response = $this->client->getResponse();

        $this->assertResponse($response, 'show_sitemap_index_relative');
    }

    public function testRedirectResponse()
    {
        $this->client->request('GET', '/sitemap.xml');

        $response = $this->client->getResponse();

        $this->assertResponseCode($response, 301);
        $this->assertTrue($response->isRedirect());

        $location = $response->headers->get('Location');
        $this->assertContains('sitemap_index.xml', $location);
    }
}
