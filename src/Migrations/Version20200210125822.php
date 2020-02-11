<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210125822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignation_chambre (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignation_chambre_option_service (assignation_chambre_id INT NOT NULL, option_service_id INT NOT NULL, INDEX IDX_D1A96D68D3ABDB0 (assignation_chambre_id), INDEX IDX_D1A96D6835B1D6 (option_service_id), PRIMARY KEY(assignation_chambre_id, option_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignation_menage_option_service (assignation_menage_id INT NOT NULL, option_service_id INT NOT NULL, INDEX IDX_EA418FC8518FEDC1 (assignation_menage_id), INDEX IDX_EA418FC835B1D6 (option_service_id), PRIMARY KEY(assignation_menage_id, option_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignation_chambre_option_service ADD CONSTRAINT FK_D1A96D68D3ABDB0 FOREIGN KEY (assignation_chambre_id) REFERENCES assignation_chambre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_chambre_option_service ADD CONSTRAINT FK_D1A96D6835B1D6 FOREIGN KEY (option_service_id) REFERENCES option_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_menage_option_service ADD CONSTRAINT FK_EA418FC8518FEDC1 FOREIGN KEY (assignation_menage_id) REFERENCES assignation_menage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_menage_option_service ADD CONSTRAINT FK_EA418FC835B1D6 FOREIGN KEY (option_service_id) REFERENCES option_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_menage DROP INDEX UNIQ_A4A3644C9B177F54, ADD INDEX IDX_A4A3644C9B177F54 (chambre_id)');
        $this->addSql('ALTER TABLE assignation_menage DROP FOREIGN KEY FK_A4A3644C35B1D6');
        $this->addSql('DROP INDEX IDX_A4A3644C35B1D6 ON assignation_menage');
        $this->addSql('ALTER TABLE assignation_menage DROP option_service_id, CHANGE chambre_id chambre_id INT NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_chambre_option_service DROP FOREIGN KEY FK_D1A96D68D3ABDB0');
        $this->addSql('DROP TABLE assignation_chambre');
        $this->addSql('DROP TABLE assignation_chambre_option_service');
        $this->addSql('DROP TABLE assignation_menage_option_service');
        $this->addSql('ALTER TABLE assignation_menage DROP INDEX IDX_A4A3644C9B177F54, ADD UNIQUE INDEX UNIQ_A4A3644C9B177F54 (chambre_id)');
        $this->addSql('ALTER TABLE assignation_menage ADD option_service_id INT DEFAULT NULL, CHANGE chambre_id chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignation_menage ADD CONSTRAINT FK_A4A3644C35B1D6 FOREIGN KEY (option_service_id) REFERENCES option_service (id)');
        $this->addSql('CREATE INDEX IDX_A4A3644C35B1D6 ON assignation_menage (option_service_id)');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }
}
