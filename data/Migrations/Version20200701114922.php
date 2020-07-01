<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701114922 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_2F57B37A12469DE2');
        $this->addSql('DROP INDEX UNIQ_2F57B37A989D9B62');
        $this->addSql('DROP INDEX UNIQ_2F57B37A5E237E06');
        $this->addSql('CREATE TEMPORARY TABLE __temp__figure AS SELECT id, category_id, name, description, slug, created_at, last_modified FROM figure');
        $this->addSql('DROP TABLE figure');
        $this->addSql('CREATE TABLE figure (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, slug VARCHAR(100) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, last_modified DATETIME DEFAULT NULL, CONSTRAINT FK_2F57B37A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO figure (id, category_id, name, description, slug, created_at, last_modified) SELECT id, category_id, name, description, slug, created_at, last_modified FROM __temp__figure');
        $this->addSql('DROP TABLE __temp__figure');
        $this->addSql('CREATE INDEX IDX_2F57B37A12469DE2 ON figure (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A989D9B62 ON figure (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A5E237E06 ON figure (name)');
        $this->addSql('DROP INDEX IDX_16DB4F895C011B5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, figure_id, extension, alt FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, figure_id INTEGER UNSIGNED NOT NULL, extension VARCHAR(5) NOT NULL COLLATE BINARY, alt VARCHAR(50) NOT NULL COLLATE BINARY, CONSTRAINT FK_16DB4F895C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture (id, figure_id, extension, alt) SELECT id, figure_id, extension, alt FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F895C011B5 ON picture (figure_id)');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER UNSIGNED DEFAULT NULL, selector VARCHAR(20) NOT NULL COLLATE BINARY, hashed_token VARCHAR(100) NOT NULL COLLATE BINARY, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C5C011B5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, figure_id, video_id, platform FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, figure_id INTEGER UNSIGNED NOT NULL, video_id VARCHAR(25) NOT NULL COLLATE BINARY, platform VARCHAR(8) NOT NULL COLLATE BINARY, CONSTRAINT FK_7CC7DA2C5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO video (id, figure_id, video_id, platform) SELECT id, figure_id, video_id, platform FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C5C011B5 ON video (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_2F57B37A5E237E06');
        $this->addSql('DROP INDEX UNIQ_2F57B37A989D9B62');
        $this->addSql('DROP INDEX IDX_2F57B37A12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__figure AS SELECT id, category_id, name, description, slug, created_at, last_modified FROM figure');
        $this->addSql('DROP TABLE figure');
        $this->addSql('CREATE TABLE figure (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(100) NOT NULL, description CLOB NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, last_modified DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO figure (id, category_id, name, description, slug, created_at, last_modified) SELECT id, category_id, name, description, slug, created_at, last_modified FROM __temp__figure');
        $this->addSql('DROP TABLE __temp__figure');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A5E237E06 ON figure (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37A989D9B62 ON figure (slug)');
        $this->addSql('CREATE INDEX IDX_2F57B37A12469DE2 ON figure (category_id)');
        $this->addSql('DROP INDEX IDX_16DB4F895C011B5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, figure_id, extension, alt FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, figure_id INTEGER UNSIGNED NOT NULL, extension VARCHAR(5) NOT NULL, alt VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO picture (id, figure_id, extension, alt) SELECT id, figure_id, extension, alt FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F895C011B5 ON picture (figure_id)');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER UNSIGNED DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C5C011B5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, figure_id, video_id, platform FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, figure_id INTEGER UNSIGNED NOT NULL, video_id VARCHAR(25) NOT NULL, platform VARCHAR(8) NOT NULL)');
        $this->addSql('INSERT INTO video (id, figure_id, video_id, platform) SELECT id, figure_id, video_id, platform FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C5C011B5 ON video (figure_id)');
    }
}
