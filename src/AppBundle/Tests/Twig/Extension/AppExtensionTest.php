<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 07.02.16
 * Time: 14:04
 */

namespace AppBundle\Tests\Twig\Extension;


class AppExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @dataProvider limitWordsData
     */
    public function testLimitWords($result, $inData)
    {
        $te = $this->getMockBuilder('AppBundle\Twig\Extension\AppExtension')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $this->assertEquals($result, trim($te->limitWords($inData[0], $inData[1])));

    }

    public function limitWordsData()
    {
        return array(
            array('lorem ipsum', array('lorem ipsum dolar siptic', 2)),
            array('Sed', array('Sed ante magna, tincidunt eleifend elementum', 1)),
            array('Proin et orci congue,', array('Proin et orci congue, facilisis dui at, mattis mauris.', 4)),
            array('Proin et orci', array('Proin et orci', 6))
        );
    }
}

