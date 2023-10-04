<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004095513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE affiliation (id INT AUTO_INCREMENT NOT NULL, facstaff_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', department_id INT DEFAULT NULL, INDEX IDX_EA721530E042B71C (facstaff_id), INDEX IDX_EA721530AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, requester_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', institution_id INT DEFAULT NULL, assignee_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', final_equiv1_course_id INT DEFAULT NULL, final_equiv2_course_id INT DEFAULT NULL, final_equiv3_course_id INT DEFAULT NULL, final_equiv4_course_id INT DEFAULT NULL, serial_num INT NOT NULL, req_admin VARCHAR(6) NOT NULL, institution_other VARCHAR(120) DEFAULT NULL, institution_country VARCHAR(90) DEFAULT NULL, course_subj_code VARCHAR(12) DEFAULT NULL, course_crse_num VARCHAR(12) DEFAULT NULL, course_term VARCHAR(24) DEFAULT NULL, course_credit_hrs VARCHAR(6) DEFAULT NULL, course_credit_basis VARCHAR(12) DEFAULT NULL, lab_subj_code VARCHAR(12) NOT NULL, lab_crse_num VARCHAR(12) DEFAULT NULL, lab_term VARCHAR(24) DEFAULT NULL, lab_credit_hrs VARCHAR(6) DEFAULT NULL, lab_credit_basis VARCHAR(12) DEFAULT NULL, phase VARCHAR(12) NOT NULL, status INT DEFAULT 1 NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, draft_equiv1_course VARCHAR(12) DEFAULT NULL, draft_equiv1_credit_hrs VARCHAR(6) DEFAULT NULL, draft_equiv1_operator VARCHAR(6) DEFAULT NULL, draft_equiv2_course VARCHAR(12) DEFAULT NULL, draft_equiv2_credit_hrs VARCHAR(6) DEFAULT NULL, draft_equiv2_operator VARCHAR(6) DEFAULT NULL, draft_equiv3_course VARCHAR(12) DEFAULT NULL, draft_equiv3_credit_hrs VARCHAR(6) DEFAULT NULL, draft_equiv3_operator VARCHAR(6) DEFAULT NULL, draft_equiv4_course VARCHAR(12) DEFAULT NULL, draft_equiv4_credit_hours VARCHAR(6) DEFAULT NULL, draft_policy VARCHAR(12) DEFAULT NULL, final_equiv1_credit_hrs VARCHAR(6) DEFAULT NULL, final_equiv1_operator VARCHAR(6) DEFAULT NULL, final_equiv2_credit_hrs VARCHAR(6) DEFAULT NULL, final_equiv2_operator VARCHAR(6) DEFAULT NULL, final_equiv3_credit_hrs VARCHAR(6) DEFAULT NULL, final_equiv3_operator VARCHAR(6) DEFAULT NULL, final_equiv4_credit_hrs VARCHAR(6) DEFAULT NULL, final_policy VARCHAR(12) DEFAULT NULL, requester_type VARCHAR(24) DEFAULT NULL, course_in_sis INT DEFAULT NULL, transcript_on_hand INT DEFAULT NULL, hold_for_requester_admit INT DEFAULT NULL, hold_for_course_input INT DEFAULT NULL, hold_for_transcript INT DEFAULT NULL, tag_spot_articulated INT DEFAULT NULL, tag_r1_to_student INT DEFAULT NULL, tag_dept_to_student INT DEFAULT NULL, tag_dept_to_r1 INT DEFAULT NULL, tag_r2_to_student INT DEFAULT NULL, tag_r2_to_dept INT DEFAULT NULL, tag_reassigned INT DEFAULT NULL, d7_nid INT DEFAULT NULL, INDEX IDX_1323A575ED442CF4 (requester_id), INDEX IDX_1323A57510405986 (institution_id), INDEX IDX_1323A57559EC7D60 (assignee_id), INDEX IDX_1323A57572B18BC7 (final_equiv1_course_id), INDEX IDX_1323A575EB53EDC6 (final_equiv2_course_id), INDEX IDX_1323A5752ADD3206 (final_equiv3_course_id), INDEX IDX_1323A5753E62785 (final_equiv4_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT DEFAULT NULL, author_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', body LONGTEXT DEFAULT NULL, body_anon LONGTEXT DEFAULT NULL, visible_to_requester INT DEFAULT 0, created DATETIME DEFAULT NULL, d7_nid INT DEFAULT NULL, INDEX IDX_CFBDFA14456C5646 (evaluation_id), INDEX IDX_CFBDFA14F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trail (id INT AUTO_INCREMENT NOT NULL, evaluation_id INT DEFAULT NULL, body LONGTEXT DEFAULT NULL, body_anon LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, d7_nid INT DEFAULT NULL, INDEX IDX_B268858F456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affiliation ADD CONSTRAINT FK_EA721530E042B71C FOREIGN KEY (facstaff_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE affiliation ADD CONSTRAINT FK_EA721530AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575ED442CF4 FOREIGN KEY (requester_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57510405986 FOREIGN KEY (institution_id) REFERENCES institution (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57559EC7D60 FOREIGN KEY (assignee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57572B18BC7 FOREIGN KEY (final_equiv1_course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575EB53EDC6 FOREIGN KEY (final_equiv2_course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5752ADD3206 FOREIGN KEY (final_equiv3_course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5753E62785 FOREIGN KEY (final_equiv4_course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trail ADD CONSTRAINT FK_B268858F456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id)');
        $this->addSql('ALTER TABLE course CHANGE status status INT DEFAULT 1, CHANGE loaded_from loaded_from VARCHAR(120) DEFAULT NULL');
        $this->addSql('ALTER TABLE department CHANGE status status INT DEFAULT 1');
        $this->addSql('ALTER TABLE institution CHANGE status status INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE category category VARCHAR(24) DEFAULT \'member\', CHANGE status status INT DEFAULT 1, CHANGE frozen frozen INT DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affiliation DROP FOREIGN KEY FK_EA721530E042B71C');
        $this->addSql('ALTER TABLE affiliation DROP FOREIGN KEY FK_EA721530AE80F5DF');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575ED442CF4');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57510405986');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57559EC7D60');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57572B18BC7');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575EB53EDC6');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5752ADD3206');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5753E62785');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14456C5646');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14F675F31B');
        $this->addSql('ALTER TABLE trail DROP FOREIGN KEY FK_B268858F456C5646');
        $this->addSql('DROP TABLE affiliation');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE trail');
        $this->addSql('ALTER TABLE institution CHANGE status status INT NOT NULL');
        $this->addSql('ALTER TABLE course CHANGE status status INT NOT NULL, CHANGE loaded_from loaded_from VARCHAR(48) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE category category VARCHAR(24) DEFAULT NULL, CHANGE status status INT DEFAULT NULL, CHANGE frozen frozen INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department CHANGE status status INT NOT NULL');
    }
}
