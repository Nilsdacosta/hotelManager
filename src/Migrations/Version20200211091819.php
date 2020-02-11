<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211091819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignation_menage (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, chambre_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_A4A3644C1B65292 (employe_id), INDEX IDX_A4A3644C9B177F54 (chambre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignation_menage_option_service (assignation_menage_id INT NOT NULL, option_service_id INT NOT NULL, INDEX IDX_EA418FC8518FEDC1 (assignation_menage_id), INDEX IDX_EA418FC835B1D6 (option_service_id), PRIMARY KEY(assignation_menage_id, option_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, tva_id INT NOT NULL, capacite VARCHAR(255) NOT NULL, etat SMALLINT NOT NULL, description LONGTEXT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_C509E4FF4D79775F (tva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, mail VARCHAR(255) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, poste SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_service (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, tva_id INT DEFAULT NULL, nom_option VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, prix_option DOUBLE PRECISION NOT NULL, INDEX IDX_F9698DA71B65292 (employe_id), INDEX IDX_F9698DA74D79775F (tva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, date_entree DATE NOT NULL, date_sortie DATE NOT NULL, status SMALLINT NOT NULL, carte_bancaire INT DEFAULT NULL, date_creation DATE NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_chambre (reservation_id INT NOT NULL, chambre_id INT NOT NULL, INDEX IDX_A29C5F7AB83297E7 (reservation_id), INDEX IDX_A29C5F7A9B177F54 (chambre_id), PRIMARY KEY(reservation_id, chambre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_option_service (reservation_id INT NOT NULL, option_service_id INT NOT NULL, INDEX IDX_EC5DCC9EB83297E7 (reservation_id), INDEX IDX_EC5DCC9E35B1D6 (option_service_id), PRIMARY KEY(reservation_id, option_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignation_menage ADD CONSTRAINT FK_A4A3644C1B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE assignation_menage ADD CONSTRAINT FK_A4A3644C9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE assignation_menage_option_service ADD CONSTRAINT FK_EA418FC8518FEDC1 FOREIGN KEY (assignation_menage_id) REFERENCES assignation_menage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignation_menage_option_service ADD CONSTRAINT FK_EA418FC835B1D6 FOREIGN KEY (option_service_id) REFERENCES option_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FF4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE option_service ADD CONSTRAINT FK_F9698DA71B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE option_service ADD CONSTRAINT FK_F9698DA74D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7AB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_chambre ADD CONSTRAINT FK_A29C5F7A9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_option_service ADD CONSTRAINT FK_EC5DCC9EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_option_service ADD CONSTRAINT FK_EC5DCC9E35B1D6 FOREIGN KEY (option_service_id) REFERENCES option_service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE assignation_menage_option_service DROP FOREIGN KEY FK_EA418FC8518FEDC1');
        $this->addSql('ALTER TABLE assignation_menage DROP FOREIGN KEY FK_A4A3644C9B177F54');
        $this->addSql('ALTER TABLE reservation_chambre DROP FOREIGN KEY FK_A29C5F7A9B177F54');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE assignation_menage DROP FOREIGN KEY FK_A4A3644C1B65292');
        $this->addSql('ALTER TABLE option_service DROP FOREIGN KEY FK_F9698DA71B65292');
        $this->addSql('ALTER TABLE assignation_menage_option_service DROP FOREIGN KEY FK_EA418FC835B1D6');
        $this->addSql('ALTER TABLE reservation_option_service DROP FOREIGN KEY FK_EC5DCC9E35B1D6');
        $this->addSql('ALTER TABLE reservation_chambre DROP FOREIGN KEY FK_A29C5F7AB83297E7');
        $this->addSql('ALTER TABLE reservation_option_service DROP FOREIGN KEY FK_EC5DCC9EB83297E7');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FF4D79775F');
        $this->addSql('ALTER TABLE option_service DROP FOREIGN KEY FK_F9698DA74D79775F');
        $this->addSql('DROP TABLE assignation_menage');
        $this->addSql('DROP TABLE assignation_menage_option_service');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE option_service');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_chambre');
        $this->addSql('DROP TABLE reservation_option_service');
        $this->addSql('DROP TABLE tva');
    }
}
