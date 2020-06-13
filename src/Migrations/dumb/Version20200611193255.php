<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200611193255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE room_types CHANGE booking_fee booking_fee NUMERIC(7, 4) DEFAULT NULL, CHANGE final_rate final_rate NUMERIC(7, 4) DEFAULT NULL, CHANGE hotel_fee hotel_fee NUMERIC(7, 4) DEFAULT NULL, CHANGE local_tax local_tax NUMERIC(7, 4) DEFAULT NULL, CHANGE net_rate net_rate NUMERIC(7, 4) DEFAULT NULL, CHANGE resort_fee resort_fee NUMERIC(7, 4) DEFAULT NULL, CHANGE service_charge service_charge NUMERIC(7, 4) DEFAULT NULL, CHANGE vat vat NUMERIC(7, 4) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE room_types CHANGE booking_fee booking_fee DOUBLE PRECISION NOT NULL, CHANGE final_rate final_rate DOUBLE PRECISION NOT NULL, CHANGE hotel_fee hotel_fee DOUBLE PRECISION NOT NULL, CHANGE local_tax local_tax DOUBLE PRECISION NOT NULL, CHANGE net_rate net_rate DOUBLE PRECISION NOT NULL, CHANGE resort_fee resort_fee DOUBLE PRECISION NOT NULL, CHANGE service_charge service_charge DOUBLE PRECISION NOT NULL, CHANGE vat vat DOUBLE PRECISION NOT NULL');
    }
}
