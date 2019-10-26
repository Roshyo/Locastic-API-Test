<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\UserVerificationRequest;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (UserVerificationRequest::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();
        if ($user === null || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(\sprintf('%s.user = :currentUser', $rootAlias));
        $queryBuilder->setParameter('currentUser', $user);
    }
}
