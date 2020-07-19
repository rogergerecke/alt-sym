<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200718185119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hostel_room_types');
        $this->addSql('ALTER TABLE room_types ADD CONSTRAINT FK_138C289BFC68ACC0 FOREIGN KEY (hostel_id) REFERENCES hostel (id)');
        $this->addSql('CREATE INDEX IDX_138C289BFC68ACC0 ON room_types (hostel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hostel_room_types (hostel_id INT NOT NULL, room_types_id INT NOT NULL, INDEX IDX_2A97AED02E54F393 (room_types_id), INDEX IDX_2A97AED0FC68ACC0 (hostel_id), PRIMARY KEY(hostel_id, room_types_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE hostel_room_types ADD CONSTRAINT FK_2A97AED02E54F393 FOREIGN KEY (room_types_id) REFERENCES room_types (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hostel_room_types ADD CONSTRAINT FK_2A97AED0FC68ACC0 FOREIGN KEY (hostel_id) REFERENCES hostel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_types DROP FOREIGN KEY FK_138C289BFC68ACC0');
        $this->addSql('DROP INDEX IDX_138C289BFC68ACC0 ON room_types');
    }
}
