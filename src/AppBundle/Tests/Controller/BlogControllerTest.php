<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 13.01.16
 * Time: 20:08
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class BlogControllerTest extends TestBaseWeb
{
    public function testShowPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'en/show/facerenam');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Facerenam', $crawler->filter('h4')->text());
    }

    public function testShowCategory()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'en/show/category/ammet');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Facerenam', $crawler->filter('h4')->text());
    }

    public function testShowTags()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'en/show/tags/culpa');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Facerenam', $crawler->filter('h4')->text());
    }

    public function testSearchAll()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'en/search/all');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('article(s)', $crawler->filter('h4')->text());
    }

}
