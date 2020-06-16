<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613063706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advertising ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE hostel ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE leisure ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE room_types ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_user_made_changes TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advertising DROP is_user_made_changes');
        $this->addSql('ALTER TABLE events DROP is_user_made_changes');
        $this->addSql('ALTER TABLE hostel DROP is_user_made_changes');
        $this->addSql('ALTER TABLE leisure DROP is_user_made_changes');
        $this->addSql('ALTER TABLE room_types DROP is_user_made_changes');
        $this->addSql('ALTER TABLE user DROP is_user_made_changes');
    }
}
