<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 11.02.16
 * Time: 19:23
 */

namespace AppBundle\Tests\Controller;


use AppBundle\Tests\TestBaseWeb;

class RegistrationControllerTest extends TestBaseWeb
{
    public function testShowPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'en/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Register', $crawler->filter('submit')->text());
    }
}
