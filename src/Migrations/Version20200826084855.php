<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826084855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, video_moderator TINYINT(1) DEFAULT NULL, video_participant TINYINT(1) DEFAULT NULL, phone_pwd TINYINT(1) DEFAULT NULL, sound_participant TINYINT(1) DEFAULT NULL, message_public TINYINT(1) DEFAULT NULL, annotation_participant TINYINT(1) DEFAULT NULL, board_participant TINYINT(1) DEFAULT NULL, record_auto TINYINT(1) DEFAULT NULL, feedback TINYINT(1) DEFAULT NULL, meeting_reminder TINYINT(1) DEFAULT NULL, personal_mailbox TINYINT(1) DEFAULT NULL, format_html_mail TINYINT(1) DEFAULT NULL, INDEX IDX_2A979110A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE parameter ADD CONSTRAINT FK_2A979110A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE parameter');
    }
}
