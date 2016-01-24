<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 13.01.16
 * Time: 20:46
 */

namespace AppBundle\Tests\Form;


use AppBundle\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'rating' => '4',
            'textComment' => 'Tests Tests'
        );
        $form = $this->factory->create(CommentType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}
