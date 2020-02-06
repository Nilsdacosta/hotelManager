<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200205162819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_menage CHANGE option_service_id option_service_id INT DEFAULT NULL, CHANGE chambre_id chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE employe ADD username VARCHAR(255) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_menage CHANGE option_service_id option_service_id INT DEFAULT NULL, CHANGE chambre_id chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_de_naissance date_de_naissance DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE employe DROP username, DROP roles, DROP password, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
