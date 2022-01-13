<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028152653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guest (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ACB79A35F85E0677 (username), UNIQUE INDEX UNIQ_ACB79A35A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guest_code (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, guest_id INT NOT NULL, cat_id INT NOT NULL, code VARCHAR(255) NOT NULL, validity DATETIME NOT NULL, is_used TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_53AF6DC5A76ED395 (user_id), UNIQUE INDEX UNIQ_53AF6DC59A4AA658 (guest_id), UNIQUE INDEX UNIQ_53AF6DC5E6ADA943 (cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guest ADD CONSTRAINT FK_ACB79A35A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guest_code ADD CONSTRAINT FK_53AF6DC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE guest_code ADD CONSTRAINT FK_53AF6DC59A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE guest_code ADD CONSTRAINT FK_53AF6DC5E6ADA943 FOREIGN KEY (cat_id) REFERENCES cat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guest_code DROP FOREIGN KEY FK_53AF6DC59A4AA658');
        $this->addSql('DROP TABLE guest');
        $this->addSql('DROP TABLE guest_code');
    }
}
