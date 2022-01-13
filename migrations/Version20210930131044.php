<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210930131044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE health (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, date DATE NOT NULL, end_date DATE DEFAULT NULL, vet_visit_motif VARCHAR(255) DEFAULT NULL, allergy VARCHAR(255) DEFAULT NULL, disease VARCHAR(255) DEFAULT NULL, wound VARCHAR(255) DEFAULT NULL, surgery VARCHAR(255) DEFAULT NULL, analysis VARCHAR(255) DEFAULT NULL, details TEXT DEFAULT NULL, document_name VARCHAR(255) DEFAULT NULL, document VARCHAR(255) DEFAULT NULL, INDEX IDX_CEDA2313E6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE health ADD CONSTRAINT FK_CEDA2313E6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE health');
    }
}
