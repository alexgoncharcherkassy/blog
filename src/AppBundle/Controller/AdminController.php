<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\PostType;

/**
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("admin/insert/post", name="insert_post")
     * @Template("@App/admin/insertPost.html.twig")
     */
    public function insertPostAction(Request $request)
    {
        $post = new Post();

        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository('AppBundle:Tag')
            ->findAll();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAll();

        $form = $this->createForm(PostType::class, $post);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $newTags = $post->getNewTags();

                if (null !== $newTags) {
                    //  $newTags = explode(',', trim($newTags));
                    foreach ($newTags as $item) {
                        if ($findTag = $em->getRepository('AppBundle:Tag')->find(trim($item))) {
                            $post->addTag($findTag);
                        } else {
                            $tag = new Tag();
                            $tag->setTagName(trim($item));
                            $tag->setWeightTag(1);
                            $em->persist($tag);
                            $post->addTag($tag);
                        }
                    }
                }

                $newCategory = $post->getNewCategory();

                if (null !== $newCategory) {
                    $item = $newCategory[0];
                    if ($findCategory = $em->getRepository('AppBundle:Category')->find(trim($item))) {
                        $post->setCategory($findCategory);
                    } else {
                        $category = new Category();
                        $category->setCategoryName(trim($item));
                        $em->persist($category);
                        $post->setCategory($category);
                    }
                }

                $post->setNewTags(null);
                $post->setNewCategory(null);
                $post->setRating(0);
                $em->persist($post);
                $em->flush();

                $this->get('app.update.tagscloud');

                return $this->redirectToRoute('homepage');
            }
        }

        return ['form' => $form->createView(), 'tags' => $tags, 'categories' => $categories];
    }

    /**
     * @Route("admin/show/", name="admin_show")
     * @Template("@App/admin/adminShow.html.twig")
     */
    public function showPostAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sql = $em->getRepository('AppBundle:Post')
            ->showAllPost();

        $paginator = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $sql,
            $this->get('request')->query->get('page', 1),
            $this->container->getParameter('knp_paginator.page_range')
        );

        if (!$posts) {
            return $this->redirectToRoute('page404');
        }

        $form_edit = [];
        $form_delete = [];
        /**
         * @var \AppBundle\Entity\Post $item
         */
        foreach ($posts as $item) {
            $form_edit[$item->getSlug()] = $this->createFormEdit($item->getSlug())->createView();
            $form_delete[$item->getSlug()] = $this->createFormDelete($item->getSlug())->createView();
        }

        return ['posts' => $posts,
            'form_edit' => $form_edit,
            'form_delete' => $form_delete];
    }

    /**
     * @Route("admin/remove/post/{slug}", name="remove_post")
     * @Method("DELETE")
     */
    public function removePostAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        $comments = $em->getRepository('AppBundle:Comment')
            ->findBy(array('post' => $post->getId()));

        if (!$post) {
            throw $this->createNotFoundException();
        }

        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        $em->remove($post);
        $em->flush();

        $this->get('app.update.tagscloud');

        return $this->redirectToRoute('admin_show');
    }

    /**
     * @Route("admin/edit/post/{slug}", name="edit_post")
     * @Template("@App/admin/editPost.html.twig")
     */
    public function editPostAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        $tags = $em->getRepository('AppBundle:Tag')
            ->findAll();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAll();
        $itemTags = $post->getTags()->getIterator();
        $itemCategory[0] = $post->getCategory();

        if (!$post) {
            return $this->redirectToRoute('page404');
        }
        $oldImage = $post->getPathImage();
        $form = $this->createForm(PostType::class, $post);

        $form_delete_image = [];
        $form_delete_image[$post->getSlug()] = $this->createFormDeleteImage($post->getSlug())->createView();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $newTags = $post->getNewTags();

            if (null !== $newTags) {
                //  $newTags = explode(',', trim($newTags));
                $post->getTags()->clear();
                foreach ($newTags as $item) {
                    $findTag = $em->getRepository('AppBundle:Tag')->find(trim($item));
                    if ($post->getTags()->contains($findTag)) {
                        continue;
                    } elseif ($findTag) {
                        $post->addTag($findTag);
                    } else {
                        $tag = new Tag();
                        $tag->setTagName(trim($item));
                        $tag->setWeightTag(1);
                        $em->persist($tag);
                        $post->addTag($tag);
                    }
                }
            } else {
                $post->getTags()->clear();
            }

            $newCategory = $post->getNewCategory();

            if (null !== $newCategory) {
                $post->setCategory(null);
                $item = $newCategory[0];
                if ($findCategory = $em->getRepository('AppBundle:Category')->find(trim($item))) {
                    $post->setCategory($findCategory);
                } else {
                    $category = new Category();
                    $category->setCategoryName(trim($item));
                    $em->persist($category);
                    $post->setCategory($category);
                }
            } else {
                $post->setCategory(null);
            }

            $post->setNewTags(null);
            $post->setNewCategory(null);

            $em->flush();

            $this->get('app.update.tagscloud');

            return $this->redirectToRoute('admin_show');
        }

        return ['form' => $form->createView(),
            'oldImage' => $oldImage,
            'formDeleteImage' => $form_delete_image,
            'slug' => $post->getSlug(),
            'tags' => $tags,
            'categories' => $categories,
            'itemTags' => $itemTags,
            'itemCategory' => $itemCategory
        ];

    }

    /**
     * @param $slug
     * @Route("admin/remove/image/{slug}", name="remove_image")
     * @Method("PUT")
     */
    public function removeImageAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        if (!$post) {
            return $this->redirectToRoute('page404');
        }
        if ($post->getPathImage() !== null && file_exists($post->getPathImage())) {
            unlink($post->getPathImage());
        }
        $post->setPathImage(null);
        $post->setNameImage(null);

        $em->flush();

        return $this->redirectToRoute('edit_post', ['slug' => $slug]);

    }

    /**
     * @param $slug
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDelete($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_post', ['slug' => $slug]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-remove btn-link',
                    'onclick' => 'return confirm("Are you sure?")'
                ]
            ])
            ->getForm();
    }

    /**
     * @param $slug
     * @return \Symfony\Component\Form\Form
     */
    private function createFormEdit($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('edit_post', ['slug' => $slug]))
            ->setMethod('PUT')
            ->add('submit', SubmitType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-pencil btn-link'
                ]
            ])
            ->getForm();
    }

    /**
     * @param $slug
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDeleteImage($slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_image', ['slug' => $slug]))
            ->setMethod('PUT')
            ->add('submit', SubmitType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-remove btn-link',
                    'onclick' => 'return confirm("Are you sure?")'
                ]
            ])
            ->getForm();
    }

}
