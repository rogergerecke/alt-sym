<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200421171341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hostel ADD CONSTRAINT FK_38FBB1679393F8FE FOREIGN KEY (partner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_38FBB1679393F8FE ON hostel (partner_id)');
        $this->addSql('ALTER TABLE hostel_rooms ADD hostel_room INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hostel DROP FOREIGN KEY FK_38FBB1679393F8FE');
        $this->addSql('DROP INDEX IDX_38FBB1679393F8FE ON hostel');
        $this->addSql('ALTER TABLE hostel_rooms DROP hostel_room');
    }
}
