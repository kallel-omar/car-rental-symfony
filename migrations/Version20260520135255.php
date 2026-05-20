<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260520135255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP cin_image, DROP license_image, DROP license_issue_date, DROP full_name, DROP phone_number');
        $this->addSql('ALTER TABLE user ADD full_name VARCHAR(255) DEFAULT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL, ADD cin_image VARCHAR(255) DEFAULT NULL, ADD license_image VARCHAR(255) DEFAULT NULL, ADD license_issue_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD cin_image VARCHAR(255) DEFAULT NULL, ADD license_image VARCHAR(255) DEFAULT NULL, ADD license_issue_date DATE DEFAULT NULL, ADD full_name VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE user DROP full_name, DROP phone_number, DROP cin_image, DROP license_image, DROP license_issue_date');
    }
}
