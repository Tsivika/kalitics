<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721144230 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription CHANGE duration duration VARCHAR(150) NOT NULL, CHANGE duration_meeting duration_meeting VARCHAR(150) NOT NULL, CHANGE number_participant number_participant VARCHAR(150) DEFAULT NULL, CHANGE price price INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription CHANGE duration duration INT NOT NULL, CHANGE duration_meeting duration_meeting INT NOT NULL, CHANGE number_participant number_participant INT NOT NULL, CHANGE price price INT NOT NULL');
    }
}
