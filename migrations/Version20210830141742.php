<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830141742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measure (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, date DATE NOT NULL, weight NUMERIC(5, 2) DEFAULT NULL, temperature NUMERIC(5, 2) DEFAULT NULL, is_in_Heat TINYINT(1) DEFAULT NULL, is_mated TINYINT(1) DEFAULT NULL, is_pregnant TINYINT(1) DEFAULT NULL, heat_end_date DATE DEFAULT NULL, INDEX IDX_80071925E6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_80071925E6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE measure');
    }
}
