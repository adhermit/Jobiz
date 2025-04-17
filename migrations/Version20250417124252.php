<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417124252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, messages LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F86AE4741E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_FBD8E0F86AE4741E ON job
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job ADD company VARCHAR(255) NOT NULL, DROP companies_id, CHANGE min_salary min_salary INT DEFAULT NULL, CHANGE max_salary max_salary INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job ADD companies_id INT DEFAULT NULL, DROP company, CHANGE min_salary min_salary INT NOT NULL, CHANGE max_salary max_salary INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F86AE4741E FOREIGN KEY (companies_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FBD8E0F86AE4741E ON job (companies_id)
        SQL);
    }
}
