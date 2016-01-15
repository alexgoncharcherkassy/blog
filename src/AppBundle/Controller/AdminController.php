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

        $form = $this->createForm(PostType::class, $post);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $newTags = $post->getNewTags();

                if (null !== $newTags) {
                    $newTags = explode(',', trim($newTags));
                    foreach ($newTags as $item) {
                        $tag = new Tag();
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

                $post->uploadImage();
                $post->setNewTags(null);
                $post->setNewCategory(null);
                $post->setRating(0);
                $em->persist($post);
                $em->flush();

                $this->updateTagsClud();

                return $this->redirectToRoute('homepage');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("admin/show/", name="admin_show")
     * @Template("@App/admin/adminShow.html.twig")
     */
    public function showPostAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showAllPost();
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

        return ['posts' => $posts, 'form_edit' => $form_edit, 'form_delete' => $form_delete];
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
        if ($post->getPathImage() !== null) {
            unlink($post->getPathImage());
        }
        $em->remove($post);
        $em->flush();

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
                $newTags = explode(',', trim($newTags));
                foreach ($newTags as $item) {
                    $tag = new Tag();
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

            $post->uploadImage();
            $post->setNewTags(null);
            $post->setNewCategory(null);

            $em->flush();
            $this->updateTagsClud();

            return $this->redirectToRoute('admin_show');
        }

        return ['form' => $form->createView(), 'oldImage' => $oldImage, 'formDeleteImage' => $form_delete_image, 'slug' => $post->getSlug()];

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

    /**
     * @return array
     */
    private function updateTagsClud()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tag')
            ->countTags();

        /**
         * @var \AppBundle\Entity\Tag $tag
         */
        foreach ($tags as $tag) {
            $countPosts = 0;
            foreach ($tag->getPosts() as $item) {
                $countPosts++;
            }
            $tag->setWeightTag($countPosts);
        }
        $em->flush();

        return;
    }

}
