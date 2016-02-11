<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 13.01.16
 * Time: 20:28
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\TestBaseWeb;

class AdminControllerTest extends TestBaseWeb
{
    public function testInsertPost()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', 'en/admin/insert/post');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Add', $crawler->filter('h4')->text());
    }

    public function testShowPost()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', 'en/admin/show/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('h4')->text());
    }

    public function testEditPost()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', 'en/admin/edit/post/Facerenam');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Edit', $crawler->filter('h4')->text());
    }
}
