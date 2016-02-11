<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 11.02.16
 * Time: 18:54
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class ProfileControllerTest extends TestBaseWeb
{
    public function testShowProfile()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/en/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Login', $crawler->filter('table')->text());
    }

    public function testEditProfile()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'user',
        ));
        $crawler = $client->request('GET', '/en/profile/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Count', $crawler->filter('table')->text());
    }
}
