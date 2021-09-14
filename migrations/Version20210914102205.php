<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914102205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pet_care (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, date DATE NOT NULL, end_date DATE DEFAULT NULL, food_type VARCHAR(255) DEFAULT NULL, food_quantity INT DEFAULT NULL, food_brand VARCHAR(255) DEFAULT NULL, grooming VARCHAR(255) DEFAULT NULL, claws TINYINT(1) DEFAULT NULL, eyes_ears VARCHAR(255) DEFAULT NULL, teeth VARCHAR(255) DEFAULT NULL, litterbox TINYINT(1) DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, INDEX IDX_7A5B4D2FE6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pet_care ADD CONSTRAINT FK_7A5B4D2FE6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pet_care');
    }
}
