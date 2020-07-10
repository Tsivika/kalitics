<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200709154800 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guide (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, duration INT NOT NULL, password VARCHAR(255) DEFAULT NULL, identifiant VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_F515E139A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, duration INT NOT NULL, mode VARCHAR(150) NOT NULL, duration_meeting INT NOT NULL, number_participant INT NOT NULL, messaging_instant TINYINT(1) NOT NULL, screen_sharing TINYINT(1) NOT NULL, recording_meeting TINYINT(1) NOT NULL, reminder_meeting TINYINT(1) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E139A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD subscription_id INT DEFAULT NULL, ADD language VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499A1887DC ON user (subscription_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499A1887DC');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP INDEX UNIQ_8D93D6499A1887DC ON user');
        $this->addSql('ALTER TABLE user DROP subscription_id, DROP language');
    }
}
