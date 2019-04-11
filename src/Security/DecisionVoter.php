<?php


namespace App\Security;
use App\Entity\Contributor;
use App\Entity\Decision;
//use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
//use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use LogicException;
class DecisionVoter extends Voter
{
    const DECIDE = 'decide';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * DecisionVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param $attributes
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function supports($attributes, $subject){
        // If the voter receive an $attribute different to 'decide' , will send an ACCESS_ABSTAIN
        if(!in_array($attributes, [self::DECIDE])){
            return false;
        }
        // If the voter receive an $subject different to Decision object , will send an ACCESS_ABSTAIN
        if(!$subject instanceof Decision){
            return false;
        }
        return true;
    }
    /**
     * @param $attribute
     * @param $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token){
        // If  the $contributor must be logged in , if Not the voter DENY ACCESS
        $contributor = $token->getUser();
        if(!$contributor instanceof Contributor){
            return false;
        }
        // If  $attribute is equal to  'decide' , the voter will call canDecide function
        if($attribute == self::DECIDE){
            return $this->canDecide($subject, $contributor, $token);
        }
        // Or throw a LogicException
        throw new LogicException('Vous êtes interdit pour décider');
    }

    /**
     * @param Decision $decision
     * @param Contributor $contributor
     * @param TokenInterface\ $token
     * @return  bool
     */
    public function canDecide(Decision $decision, Contributor $contributor,TokenInterface $token){
        if($this->decisionManager->decide($token,['ROLE_ADMIN'])){
            return true;
        }
        return $decision->getContributor()->getId() == $contributor->getId();
    }
}