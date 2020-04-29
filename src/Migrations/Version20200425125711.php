<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425125711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE room_types (id INT AUTO_INCREMENT NOT NULL, hostel_id INT NOT NULL, booking_fee DOUBLE PRECISION NOT NULL, breakfast_included TINYINT(1) NOT NULL, currency VARCHAR(3) NOT NULL, discounts JSON DEFAULT NULL, final_rate DOUBLE PRECISION NOT NULL, free_cancellation TINYINT(1) NOT NULL, hotel_fee DOUBLE PRECISION NOT NULL, rate_type VARCHAR(7) DEFAULT NULL, local_tax DOUBLE PRECISION NOT NULL, meal_code VARCHAR(2) NOT NULL, landing_page_url VARCHAR(255) DEFAULT NULL, net_rate DOUBLE PRECISION NOT NULL, payment_type VARCHAR(255) NOT NULL, resort_fee DOUBLE PRECISION NOT NULL, room_amenities JSON DEFAULT NULL, room_code VARCHAR(255) NOT NULL, service_charge DOUBLE PRECISION NOT NULL, url VARCHAR(255) DEFAULT NULL, vat DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hostel ADD api_key VARCHAR(32) DEFAULT NULL, ADD hostel_availability_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE room_types');
        $this->addSql('ALTER TABLE hostel DROP api_key, DROP hostel_availability_url');
    }
}
