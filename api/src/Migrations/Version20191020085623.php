<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020085623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user_verification_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, idimage_path VARCHAR(511) NOT NULL, comment CLOB DEFAULT NULL, status VARCHAR(63) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9EDC0C31A76ED395 ON user_verification_request (user_id)');
        $this->addSql('DROP TABLE greeting');
        $this->addSql('DROP INDEX IDX_BA5AE01D7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, owner_id, title, content, publication_date FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, publication_date DATETIME NOT NULL, CONSTRAINT FK_BA5AE01D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO blog_post (id, owner_id, title, content, publication_date) SELECT id, owner_id, title, content, publication_date FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D7E3C61F9 ON blog_post (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE greeting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('DROP TABLE user_verification_request');
        $this->addSql('DROP INDEX IDX_BA5AE01D7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, owner_id, title, content, publication_date FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, content CLOB NOT NULL, publication_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO blog_post (id, owner_id, title, content, publication_date) SELECT id, owner_id, title, content, publication_date FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D7E3C61F9 ON blog_post (owner_id)');
    }
}
