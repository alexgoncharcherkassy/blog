<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 11.02.16
 * Time: 19:25
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class SecurityControllerTest extends TestBaseWeb
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Sign', $crawler->filter('submit')->text());
    }
}
