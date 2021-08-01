<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711201452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accident (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, place VARCHAR(255) NOT NULL, accident_date VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_8F31DB6F545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carburant (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, nature VARCHAR(255) NOT NULL, carburant_date VARCHAR(255) NOT NULL, quantity VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, invoice LONGBLOB NOT NULL, INDEX IDX_B46A330A545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE insurance (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, agence VARCHAR(255) NOT NULL, insurance_date VARCHAR(255) NOT NULL, insurance_expire VARCHAR(255) NOT NULL, cost VARCHAR(255) NOT NULL, invoice LONGBLOB NOT NULL, nature VARCHAR(255) NOT NULL, INDEX IDX_640EAF4C545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, provider_id INT DEFAULT NULL, nature VARCHAR(255) NOT NULL, maintenance_date VARCHAR(255) NOT NULL, cost VARCHAR(255) NOT NULL, invoice LONGBLOB NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_2F84F8E9545317D1 (vehicle_id), UNIQUE INDEX UNIQ_2F84F8E9A53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, nature VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technique (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, nature VARCHAR(255) NOT NULL, agence VARCHAR(255) NOT NULL, number_agence VARCHAR(255) NOT NULL, date_visite VARCHAR(255) NOT NULL, technique_expire VARCHAR(255) NOT NULL, cost VARCHAR(255) NOT NULL, invoice LONGBLOB NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_D73B9841545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accident ADD CONSTRAINT FK_8F31DB6F545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE carburant ADD CONSTRAINT FK_B46A330A545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE insurance ADD CONSTRAINT FK_640EAF4C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9A53A8AA FOREIGN KEY (provider_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE technique ADD CONSTRAINT FK_D73B9841545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accident');
        $this->addSql('DROP TABLE carburant');
        $this->addSql('DROP TABLE insurance');
        $this->addSql('DROP TABLE maintenance');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE technique');
    }
}
