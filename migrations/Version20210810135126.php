<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210810135126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, INDEX IDX_D4E6F81E6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cat (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, race VARCHAR(255) DEFAULT NULL, coat VARCHAR(255) DEFAULT NULL, is_sterilized TINYINT(1) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, microchip VARCHAR(255) DEFAULT NULL, tattoo VARCHAR(255) DEFAULT NULL, owner_name VARCHAR(255) DEFAULT NULL, veterinary_name VARCHAR(255) DEFAULT NULL, INDEX IDX_9E5E43A87E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81E6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
        $this->addSql('ALTER TABLE cat ADD CONSTRAINT FK_9E5E43A87E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81E6ADA943');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE cat');
    }
}
