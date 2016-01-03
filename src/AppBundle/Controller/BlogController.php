<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 01.01.16
 * Time: 13:16
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BlogController
 * @package AppBundle\Controller
 */
class BlogController extends Controller
{
    /**
     * @Route("/show/mostpopular", name="most_popular")
     * @Template("@App/default/sidebar/sidebar1.html.twig")
     */
    public function showMostPopularAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/lastcomment", name="last_comment")
     * @Template("@App/default/sidebar/sidebar2.html.twig")
     */
    public function showLastCommentAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')
            ->showLastFiveComment();

        $c = $comments;

        return ['comments' => $comments];
    }

    /**
     * @Route("/show/{slug}", name="show_post")
     * @Template("@App/blog/showPost.html.twig")
     */
    public function showPostAction($slug, Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $comment->setPost($post);
                $em->persist($comment);
                $em->flush();
                $this->changeRating($slug);

                return $this->redirectToRoute('show_post', array('slug' => $slug));
            }
        }

        $posts = $em->getRepository('AppBundle:Post')
            ->showPost($slug);

        return ['posts' => $posts, 'form' => $form->createView()];
    }

    /**
     * @Route("/show/category/{slug}", name="show_category")
     * @Template("@App/default/index.html.twig")
     */
    public function showCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showCategoryPost($slug);

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/tags/{slug}", name="show_tags")
     * @Template("@App/default/index.html.twig")
     */
    public function showTagsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showTagsPost($slug);

        return ['posts' => $posts];
    }

    private function changeRating($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        $comments = $em->getRepository('AppBundle:Comment')
            ->findBy(array('post' => $post));

        $count = count($comments);
        $summ = 0;

        foreach ($comments as $comment) {
            $summ += $comment->getRating();
        }
        $rating = $summ / $count;

        $post->setRating($rating);
        $em->flush();

        return;
    }
}