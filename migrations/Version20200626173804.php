<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626173804 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_types ADD accommodation_type VARCHAR(255) NOT NULL, ADD number_of_units INT DEFAULT NULL, ADD unit_size DOUBLE PRECISION DEFAULT NULL, ADD unit_type VARCHAR(4) DEFAULT NULL, ADD unit_occupancy INT DEFAULT NULL, ADD number_of_bedrooms DOUBLE PRECISION DEFAULT NULL, ADD number_of_bathrooms DOUBLE PRECISION DEFAULT NULL, ADD floor_number INT DEFAULT NULL, ADD unit_number VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room_types DROP accommodation_type, DROP number_of_units, DROP unit_size, DROP unit_type, DROP unit_occupancy, DROP number_of_bedrooms, DROP number_of_bathrooms, DROP floor_number, DROP unit_number');
    }
}
