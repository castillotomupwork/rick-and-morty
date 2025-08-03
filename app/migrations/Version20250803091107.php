<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803091107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `characters` (id INT AUTO_INCREMENT NOT NULL, origin_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, species VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) NOT NULL, image LONGTEXT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_3A29410E56A273CC (origin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `dimensions` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `dimensions_locations_characters` (id INT AUTO_INCREMENT NOT NULL, dimension_id INT NOT NULL, location_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_9D07DE2F277428AD (dimension_id), INDEX IDX_9D07DE2F64D218E (location_id), INDEX IDX_9D07DE2F1136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `episodes` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, air_date VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `episodes_charactes` (id INT AUTO_INCREMENT NOT NULL, episode_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_7ADC7631362B62A0 (episode_id), INDEX IDX_7ADC76311136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `locations` (id INT AUTO_INCREMENT NOT NULL, dimension_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, url LONGTEXT DEFAULT NULL, INDEX IDX_17E64ABA277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `characters` ADD CONSTRAINT FK_3A29410E56A273CC FOREIGN KEY (origin_id) REFERENCES `locations` (id)');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` ADD CONSTRAINT FK_9D07DE2F277428AD FOREIGN KEY (dimension_id) REFERENCES `dimensions` (id)');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` ADD CONSTRAINT FK_9D07DE2F64D218E FOREIGN KEY (location_id) REFERENCES `locations` (id)');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` ADD CONSTRAINT FK_9D07DE2F1136BE75 FOREIGN KEY (character_id) REFERENCES `characters` (id)');
        $this->addSql('ALTER TABLE `episodes_charactes` ADD CONSTRAINT FK_7ADC7631362B62A0 FOREIGN KEY (episode_id) REFERENCES `episodes` (id)');
        $this->addSql('ALTER TABLE `episodes_charactes` ADD CONSTRAINT FK_7ADC76311136BE75 FOREIGN KEY (character_id) REFERENCES `characters` (id)');
        $this->addSql('ALTER TABLE `locations` ADD CONSTRAINT FK_17E64ABA277428AD FOREIGN KEY (dimension_id) REFERENCES `dimensions` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `characters` DROP FOREIGN KEY FK_3A29410E56A273CC');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` DROP FOREIGN KEY FK_9D07DE2F277428AD');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` DROP FOREIGN KEY FK_9D07DE2F64D218E');
        $this->addSql('ALTER TABLE `dimensions_locations_characters` DROP FOREIGN KEY FK_9D07DE2F1136BE75');
        $this->addSql('ALTER TABLE `episodes_charactes` DROP FOREIGN KEY FK_7ADC7631362B62A0');
        $this->addSql('ALTER TABLE `episodes_charactes` DROP FOREIGN KEY FK_7ADC76311136BE75');
        $this->addSql('ALTER TABLE `locations` DROP FOREIGN KEY FK_17E64ABA277428AD');
        $this->addSql('DROP TABLE `characters`');
        $this->addSql('DROP TABLE `dimensions`');
        $this->addSql('DROP TABLE `dimensions_locations_characters`');
        $this->addSql('DROP TABLE `episodes`');
        $this->addSql('DROP TABLE `episodes_charactes`');
        $this->addSql('DROP TABLE `locations`');
    }
}
