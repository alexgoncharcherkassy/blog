<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 08.02.16
 * Time: 19:46
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser() ? $token->getUser() : null;

        if (!$user instanceof User) {

            return false;
        }

        $profile = $subject;

        switch($attribute) {
            case self::VIEW:
                return $this->canView($profile, $user);
            case self::EDIT:
                return $this->canEdit($profile, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView($profile, $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($profile, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit($profile, $user)
    {
        if ($user == $profile) {

            return true;
        }

        return new AccessDeniedException();
    }
}