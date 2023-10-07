<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231007164553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliation ADD loaded_from VARCHAR(48) DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD loaded_from VARCHAR(48) DEFAULT NULL, DROP body_anon');
        $this->addSql('ALTER TABLE trail ADD loaded_from VARCHAR(48) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliation DROP loaded_from');
        $this->addSql('ALTER TABLE note ADD body_anon LONGTEXT DEFAULT NULL, DROP loaded_from');
        $this->addSql('ALTER TABLE trail DROP loaded_from');
    }
}
