<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 01.01.16
 * Time: 13:16
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class BlogController
 * @package AppBundle\Controller
 */
class BlogController extends Controller
{

    /**
     * @Route("/{_locale}/show/{slug}", name="show_post", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/blog/showPost.html.twig")
     */
    public function showPostAction($slug, Request $request)
    {
        $comment = new Comment();
        $user = $this->getUser();

        $form = $this->createForm(CommentType::class, $comment);
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        $admin = $em->getRepository('AppBundle:User')
            ->findOneBy(array('username' => 'admin'));
        if (!$post) {
            return $this->redirectToRoute('page404');
        }

        if ($request->getMethod() == 'POST') {
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
            $form->handleRequest($request);
            if ($form->isValid()) {
                $comment->setPost($post);
                $comment->setAuthor($user);
                $em->persist($comment);
                $em->flush();

                $this->get('app.change.rating')->changeRating($slug);

                return $this->redirectToRoute('show_post', array('slug' => $slug));
            }
        }

        $posts = $em->getRepository('AppBundle:Post')
            ->showPost($slug);
        /**
         * @var \AppBundle\Entity\Post $items
         * @var \AppBundle\Entity\Comment $item
         */
        $form_delete_comment = [];
        foreach ($posts as $items) {
            foreach ($items->getComments() as $item)
                if ($this->get('app.check.roles')->checkRole($user, $admin, $items, $item)) {
                    $form_delete_comment[$item->getId()] =
                        $this->createFormDeleteComment($item->getId(), $slug)->createView();
                } else {
                    $form_delete_comment[$item->getId()] = $this->createFormBuilder()
                        ->getForm()
                        ->createView();
                }
        }
        return [
            'posts' => $posts,
            'form' => $form->createView(),
            'formDeleteComment' => $form_delete_comment,
        ];
    }

    /**
     * @Route("/{_locale}/show/category/{slug}", name="show_category", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/blog/showCategoryTags.html.twig")
     */
    public function showCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        if ($slug === '#') {
            $posts = $em->getRepository('AppBundle:Post')
                ->showPostWithoutCategory();
            if (!$posts) {
                return $this->redirectToRoute('page404');
            }

            return ['posts' => $posts];
        }

        $posts = $em->getRepository('AppBundle:Post')
            ->showCategoryPost($slug);
        if (!$posts) {
            return $this->redirectToRoute('page404');
        }

        return ['posts' => $posts];
    }

    /**
     * @Route("/{_locale}/show/tags/{slug}", name="show_tags", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/blog/showCategoryTags.html.twig")
     */
    public function showTagsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showTagsPost($slug);
        if (!$posts) {
            return $this->redirectToRoute('page404');
        }

        return ['posts' => $posts];
    }

    /**
     * @Route("/{_locale}/show/users/{slug}", name="show_users", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/blog/showCategoryTags.html.twig")
     */
    public function showUsersAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showUsers($slug);
        if (!$posts) {
            return $this->redirectToRoute('page404');
        }

        return ['posts' => $posts];
    }

    /**
     * @param $slug
     * @Route("/{_locale}/remove/comment/{id}/{slug}", name="remove_comment", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Method("DELETE")
     */
    public function removeCommentAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = $em->getRepository('AppBundle:Comment')
            ->find($id);

        $em->remove($comment);
        $em->flush();

        $this->get('app.change.rating')->changeRating($slug);

        return $this->redirectToRoute('show_post', ['slug' => $slug]);

    }

    /**
     * @param $id
     * @param $slug
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDeleteComment($id, $slug)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_comment', ['id' => $id, 'slug' => $slug]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-remove btn-link'
                ]
            ])
            ->getForm();
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/{_locale}/search", name="search_show", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     */
    public function searchShowAction(Request $request)
    {
        $data = $request->request->get('dataSearch');

        $template = $this->forward('AppBundle:Blog:search',
            array('data' => $data))
            ->getContent();

        $response = new Response($template, 200);

        return $response;
    }

    /**
     * @Route("/{_locale}/search/ajax", name="search_ajax", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/default/searchData.html.twig")
     */
    public function searchAction($data)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->search($data);

        return ['posts' => $posts];
    }

    /**
     * @Route("/{_locale}/search/all", name="search_all", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/default/searchAllData.html.twig")
     */
    public function searchAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->get('srch-term');

        $posts = $em->getRepository('AppBundle:Post')
            ->search($data);
        /* if (!$posts) {
             return $this->redirectToRoute('page404');
         }*/

        return ['posts' => $posts];
    }


}
