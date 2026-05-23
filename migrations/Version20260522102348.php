<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260522102348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rental_process (id INT AUTO_INCREMENT NOT NULL, pickup_type VARCHAR(50) DEFAULT NULL, delivery_address LONGTEXT DEFAULT NULL, pickup_fuel_level VARCHAR(50) DEFAULT NULL, pickup_kilometers INT DEFAULT NULL, return_fuel_level VARCHAR(50) DEFAULT NULL, return_kilometers INT DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, pickup_time TIME DEFAULT NULL, reservation_id INT NOT NULL, car_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_55FBC279B83297E7 (reservation_id), INDEX IDX_55FBC279C3C6F69F (car_id), INDEX IDX_55FBC279A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE rental_process ADD CONSTRAINT FK_55FBC279B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE rental_process ADD CONSTRAINT FK_55FBC279C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE rental_process ADD CONSTRAINT FK_55FBC279A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rental_process DROP FOREIGN KEY FK_55FBC279B83297E7');
        $this->addSql('ALTER TABLE rental_process DROP FOREIGN KEY FK_55FBC279C3C6F69F');
        $this->addSql('ALTER TABLE rental_process DROP FOREIGN KEY FK_55FBC279A76ED395');
        $this->addSql('DROP TABLE rental_process');
    }
}
