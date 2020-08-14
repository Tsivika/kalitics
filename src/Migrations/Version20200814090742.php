<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814090742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guide ADD category_id INT DEFAULT NULL, ADD question VARCHAR(255) NOT NULL, ADD response LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE guide ADD CONSTRAINT FK_CA9EC73512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_CA9EC73512469DE2 ON guide (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide DROP FOREIGN KEY FK_CA9EC73512469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_CA9EC73512469DE2 ON guide');
        $this->addSql('ALTER TABLE guide DROP category_id, DROP question, DROP response');
    }
}
