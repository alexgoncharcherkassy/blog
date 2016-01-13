<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 13.01.16
 * Time: 20:35
 */

namespace AppBundle\Tests\Form;

use AppBundle\Form\PostType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PostTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $childType = new PostType();
        return array(new PreloadedExtension(array(
            $childType->getName() => $childType,
        ), array()));
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'titlePost' => 'Tests',
            'textPost' => 'Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests Tests',
            'newTags' => 'Test',
            'tags' => '',
            'newCategory' => 'Test',
            'category' => ''
        );
        $form = $this->factory->create(PostType::class);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
