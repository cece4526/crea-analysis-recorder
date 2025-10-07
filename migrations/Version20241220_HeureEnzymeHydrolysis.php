<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les propriétés d'hydrolyse enzymatique à HeureEnzyme
 */
final class Version20241220_HeureEnzymeHydrolysis extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes pour l\'hydrolyse enzymatique dans la table HeureEnzyme';
    }

    public function up(Schema $schema): void
    {
        // Ajout des nouvelles colonnes pour l'hydrolyse enzymatique
        $this->addSql('ALTER TABLE heure_enzyme ADD type_enzyme VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD quantite_enzyme NUMERIC(10, 3) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD unite_quantite VARCHAR(20) DEFAULT NULL');  
        $this->addSql('ALTER TABLE heure_enzyme ADD temperature_initiale NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD temperature_finale NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD temperature_moyenne NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD ph_initial NUMERIC(4, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD ph_final NUMERIC(4, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD ph_moyen NUMERIC(4, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD duree_planifiee INT DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD efficacite_calculee NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE heure_enzyme ADD conformite TINYINT(1) DEFAULT 1');
        
        // Mise à jour des valeurs par défaut pour les enregistrements existants
        $this->addSql('UPDATE heure_enzyme SET unite_quantite = \'ml\' WHERE unite_quantite IS NULL AND quantite_enzyme IS NOT NULL');
        $this->addSql('UPDATE heure_enzyme SET conformite = 1 WHERE conformite IS NULL');
        $this->addSql('UPDATE heure_enzyme SET duree_planifiee = 60 WHERE duree_planifiee IS NULL'); // 60 minutes par défaut
    }

    public function down(Schema $schema): void
    {
        // Suppression des colonnes ajoutées
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN type_enzyme');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN quantite_enzyme');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN unite_quantite');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN temperature_initiale');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN temperature_finale');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN temperature_moyenne');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN ph_initial');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN ph_final');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN ph_moyen');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN duree_planifiee');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN efficacite_calculee');
        $this->addSql('ALTER TABLE heure_enzyme DROP COLUMN conformite');
    }
}
