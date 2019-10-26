<?php

namespace App\Event\Listener;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class BlogPostListener
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof BlogPost) {
            return;
        }

        $this->assignUser($entity);
    }

    private function assignUser(BlogPost $entity): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        Assert::isInstanceOf($user, User::class);

        $entity->setOwner($user);
    }
}
