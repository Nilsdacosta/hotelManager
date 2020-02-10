<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209104656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_menage DROP INDEX UNIQ_A4A3644C9B177F54, ADD INDEX IDX_A4A3644C9B177F54 (chambre_id)');
        $this->addSql('ALTER TABLE assignation_menage CHANGE option_service_id option_service_id INT DEFAULT NULL, CHANGE chambre_id chambre_id INT NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_menage DROP INDEX IDX_A4A3644C9B177F54, ADD UNIQUE INDEX UNIQ_A4A3644C9B177F54 (chambre_id)');
        $this->addSql('ALTER TABLE assignation_menage CHANGE option_service_id option_service_id INT DEFAULT NULL, CHANGE chambre_id chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }
}