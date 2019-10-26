<?php

namespace App\Event\Listener;

use App\Entity\User;
use App\Entity\UserVerificationRequest;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class UserVerificationRequestListener
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof UserVerificationRequest) {
            return;
        }

        $this->assignUser($entity);
    }

    private function assignUser(UserVerificationRequest $entity): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        Assert::isInstanceOf($user, User::class);

        $entity->setUser($user);
    }
}
