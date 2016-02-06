<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 06.02.16
 * Time: 11:18
 */

namespace AppBundle\Service;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;

class AdminServices
{
    public function checkRole($user, User $admin, Post $post, Comment $comment)
    {
        if ($user instanceof User && $user !== null) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {

                return true;
            }
            if (in_array('ROLE_MODERATOR', $user->getRoles()) && $post->getAuthor() == $user &&
                $comment->getAuthor() != $admin || $comment->getAuthor() == $user) {

                return true;
            }
            if (in_array('ROLE_USER', $user->getRoles()) && $comment->getAuthor() == $user) {

                return true;
            }
        }

        return false;
    }

}
