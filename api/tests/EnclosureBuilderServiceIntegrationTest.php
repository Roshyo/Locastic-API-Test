<?php

namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    private function truncateEntities(array $entities): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL($this->getEntityManager()->getClassMetadata($entity)->getTableName());
            $connection->executeUpdate($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
