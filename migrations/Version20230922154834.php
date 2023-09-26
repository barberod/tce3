<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922154834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD loaded_from VARCHAR(48) DEFAULT NULL');
        $this->addSql('ALTER TABLE institution ADD loaded_from VARCHAR(48) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD d7_uid INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP d7_uid');
        $this->addSql('ALTER TABLE course DROP loaded_from');
        $this->addSql('ALTER TABLE institution DROP loaded_from');
    }
}
