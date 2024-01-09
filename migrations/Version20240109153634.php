<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109153634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation ADD course_title VARCHAR(255) DEFAULT NULL, ADD lab_title VARCHAR(255) DEFAULT NULL, CHANGE serial_num serial_num INT DEFAULT NULL, CHANGE lab_subj_code lab_subj_code VARCHAR(12) DEFAULT NULL, CHANGE phase phase VARCHAR(12) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation DROP course_title, DROP lab_title, CHANGE serial_num serial_num INT NOT NULL, CHANGE lab_subj_code lab_subj_code VARCHAR(12) NOT NULL, CHANGE phase phase VARCHAR(12) NOT NULL');
    }
}
