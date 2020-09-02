<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723080208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD subscription_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D47B330E FOREIGN KEY (subscription_user_id) REFERENCES subscription (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D47B330E ON user (subscription_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D47B330E');
        $this->addSql('DROP INDEX IDX_8D93D649D47B330E ON user');
        $this->addSql('ALTER TABLE user DROP subscription_user_id');
    }
}
