<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928142112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE health_care (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, date DATE NOT NULL, end_date DATE DEFAULT NULL, vaccine VARCHAR(255) DEFAULT NULL, injection_site VARCHAR(255) DEFAULT NULL, dewormer TINYINT(1) DEFAULT NULL, parasite VARCHAR(255) DEFAULT NULL, treatment VARCHAR(255) DEFAULT NULL, product_name VARCHAR(255) DEFAULT NULL, dosage VARCHAR(255) DEFAULT NULL, descaling VARCHAR(255) DEFAULT NULL, INDEX IDX_7C967FFAE6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE health_care ADD CONSTRAINT FK_7C967FFAE6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE health_care');
    }
}
