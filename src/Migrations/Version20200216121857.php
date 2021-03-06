<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216121857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chambre CHANGE statut_assignation_menage statut_assignation_menage SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) DEFAULT NULL, CHANGE date_de_naissance date_de_naissance DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chambre CHANGE statut_assignation_menage statut_assignation_menage SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE mail mail VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_de_naissance date_de_naissance DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE employe CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE option_service CHANGE tva_id tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE carte_bancaire carte_bancaire INT DEFAULT NULL');
    }
}
