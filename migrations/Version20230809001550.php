<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809001550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE org_id org_id INT DEFAULT NULL, CHANGE display_name display_name VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(180) DEFAULT NULL, CHANGE category category VARCHAR(24) DEFAULT NULL, CHANGE status status INT DEFAULT NULL, CHANGE created created DATETIME DEFAULT NULL, CHANGE updated updated DATETIME DEFAULT NULL, CHANGE frozen frozen INT DEFAULT NULL, CHANGE loaded_from loaded_from VARCHAR(48) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE org_id org_id INT NOT NULL, CHANGE display_name display_name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE category category VARCHAR(24) NOT NULL, CHANGE status status INT NOT NULL, CHANGE frozen frozen INT NOT NULL, CHANGE loaded_from loaded_from VARCHAR(48) NOT NULL, CHANGE created created DATETIME NOT NULL, CHANGE updated updated DATETIME NOT NULL');
    }
}
