<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200421165822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hostel_rooms (id INT AUTO_INCREMENT NOT NULL, hostel_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hostel ADD room_types VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hostel ADD CONSTRAINT FK_38FBB1679393F8FE FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_38FBB1679393F8FE ON hostel (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hostel_rooms');
        $this->addSql('ALTER TABLE hostel DROP FOREIGN KEY FK_38FBB1679393F8FE');
        $this->addSql('DROP INDEX IDX_38FBB1679393F8FE ON hostel');
        $this->addSql('ALTER TABLE hostel DROP room_types');
    }
}
