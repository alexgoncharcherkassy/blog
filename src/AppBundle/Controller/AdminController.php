<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tags;
use AppBundle\Form\ImageType;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\PostType;

class AdminController extends Controller
{
    /**
     * @Route("admin/insert/post", name="insert_post")
     * @Template("@App/admin/insertPost.html.twig")
     */
    public function insertPost(Request $request)
    {
        $post = new Post();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PostType::class, $post);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $newTags = $post->getNewTags();

                if (null !== $newTags) {
                    $newTags = explode(',', trim($newTags));
                    foreach ($newTags as $item) {
                        $tag = new Tags();
                        $tag->setTagName($item);
                        $em->persist($tag);
                        $post->addTag($tag);
                    }
                }

                $newCategory = $post->getNewCategory();

                if (null !== $newCategory) {
                    $category = new Category();
                    $category->setCategoryName(trim($newCategory));
                    $em->persist($category);
                    $post->setCategory($category);
                }

          //      $post->uploadImage('new');
                $post->setNewTags(null);
                $post->setNewCategory(null);
                $post->setRating(0);
                $em->persist($post);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("admin/insert/image", name="insert_image")
     * @Template("@App/admin/insertPost.html.twig")
     */
    public function insertImage(Request $request)
    {
        $post = new Image();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ImageType::class, $post);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($post);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }
        }

        return ['form' => $form->createView()];
    }
}
