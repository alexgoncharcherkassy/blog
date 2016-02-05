<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 03.02.16
 * Time: 21:24
 */

namespace AppBundle\Security;


use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Comment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Post $post */
        $comment = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($comment, $user, $token);
            case self::EDIT:
                return $this->canEdit($comment, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Comment $comment, User $user, TokenInterface $token)
    {
        // if they can edit, they can view
        if ($this->canEdit($comment, $user, $token)) {
            return true;
        }

        return false;
    }

    private function canEdit(Comment $comment, User $user, TokenInterface $token)
    {
       // return $user === $post->getAuthor();

        if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR')) && $comment->getAuthor() != 'admin') {
            return true;
        }

        if ($this->decisionManager->decide($token, array('ROLE_USER')) && $comment->getAuthor() == $user) {
            return true;
        }

        if ($this->decisionManager->decide($token, array('IS_AUTHENTICATED_ANONYMOUSLY'))) {
            return false;
        }

        return false;
    }
}