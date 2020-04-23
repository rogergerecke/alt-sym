<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414205100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hostel ADD address VARCHAR(255) NOT NULL, ADD address_sub VARCHAR(255) DEFAULT NULL, ADD postcode INT NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD state VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD country_id INT DEFAULT NULL, ADD longitude DOUBLE PRECISION DEFAULT NULL, ADD latitude DOUBLE PRECISION DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD fax VARCHAR(255) DEFAULT NULL, ADD web VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD currency VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hostel DROP address, DROP address_sub, DROP postcode, DROP city, DROP state, DROP country, DROP country_id, DROP longitude, DROP latitude, DROP phone, DROP fax, DROP web, DROP email, DROP currency');
    }
}
