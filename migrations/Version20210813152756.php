<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210813152756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, owner_address_cat_id INT DEFAULT NULL, veterinary_address_cat_id INT DEFAULT NULL, address1 VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, INDEX IDX_D4E6F81998BB479 (owner_address_cat_id), INDEX IDX_D4E6F8166621ECB (veterinary_address_cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cat (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, race VARCHAR(255) DEFAULT NULL, coat VARCHAR(255) DEFAULT NULL, is_sterilized TINYINT(1) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, date_of_death DATE DEFAULT NULL, microchip VARCHAR(255) DEFAULT NULL, tattoo VARCHAR(255) DEFAULT NULL, owner_name VARCHAR(255) DEFAULT NULL, veterinary_name VARCHAR(255) DEFAULT NULL, INDEX IDX_9E5E43A87E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81998BB479 FOREIGN KEY (owner_address_cat_id) REFERENCES cat (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8166621ECB FOREIGN KEY (veterinary_address_cat_id) REFERENCES cat (id)');
        $this->addSql('ALTER TABLE cat ADD CONSTRAINT FK_9E5E43A87E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81998BB479');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8166621ECB');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE cat');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
