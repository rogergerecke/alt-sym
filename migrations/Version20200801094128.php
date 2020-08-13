<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200801094128 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hostel DROP room_types');
        $this->addSql('ALTER TABLE occupancy_plan ADD CONSTRAINT FK_C06CC03DFC68ACC0 FOREIGN KEY (hostel_id) REFERENCES hostel (id)');
        $this->addSql('CREATE INDEX IDX_C06CC03DFC68ACC0 ON occupancy_plan (hostel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hostel ADD room_types VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE occupancy_plan DROP FOREIGN KEY FK_C06CC03DFC68ACC0');
        $this->addSql('DROP INDEX IDX_C06CC03DFC68ACC0 ON occupancy_plan');
    }
}
