<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200903185031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participe (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, abonne_id INT NOT NULL, date_at DATE NOT NULL, date_fin DATE DEFAULT NULL, INDEX IDX_9FFA8D471F7E88B (event_id), INDEX IDX_9FFA8D4C325A696 (abonne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participe ADD CONSTRAINT FK_9FFA8D471F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participe ADD CONSTRAINT FK_9FFA8D4C325A696 FOREIGN KEY (abonne_id) REFERENCES abonne (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participe');
    }
}
