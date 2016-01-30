<?php
namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

/**
 * Class FixturesLoader
 * @package AppBundle\DataFixtures\ORM
 */
class FixturesLoader extends DataFixtureLoader
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return array<string>
     */
    protected function getFixtures()
    {
        $env = $this->container->get('kernel')->getEnvironment();
        if ($env == 'test') {
            return [
                __DIR__ . '/DataForTests/post.yml',
            ];
        }
        return [
            __DIR__ . '/Data/post.yml',
        ];
    }
}