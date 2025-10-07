# 🏭 CRÉA-ANALYSIS RECORDER - Guide Complet

## 📋 Vue d'ensemble

**CRÉA-ANALYSIS RECORDER** est une application Symfony 7.3 de gestion complète des analyses de production industrielle pour les processus SOJA et CÉRÉALES. L'application suit un workflow strict avec validations, corrections et génération de rapports PDF.

## 🎯 Workflow Industriel

### 🌱 Production SOJA
```
Production → OF → HACCP → Analyses (1h30 max) → Corrections AV → Corrections AP → Okara → Double Validation → PDF
```

### 🌾 Production CÉRÉALES  
```
Production → OF → HACCP → Cuves → Décanteurs → Corrections AV → Corrections AP → Double Validation → PDF
```

## 🏗️ Architecture Technique

### Entités (15 modernisées avec PHP 8 Attributes)
- **Production** : Gestion des productions industrielles
- **OF** : Ordres de fabrication avec workflow
- **HACCP** : Contrôles qualité obligatoires
- **AnalyseSoja** : Analyses SOJA toutes les 1h30 max
- **CuveCereales** / **DecanteurCereales** : Équipements CÉRÉALES
- **AvCorrectSoja** / **ApCorrectSoja** : Corrections SOJA
- **AvCorrectCereales** / **ApCorrectCereales** : Corrections CÉRÉALES
- **Okara** : Gestion des déchets SOJA
- **Echantillons**, **Enzyme**, **HeureEnzyme**, **QuantiteEnzyme** : Modules complémentaires

### Contrôleurs API (/api)

#### 🔄 **WorkflowController** - Orchestration principale
- `GET /api/workflow/productions` - Liste des productions
- `GET /api/workflow/productions/{id}/ofs` - OF d'une production
- `POST /api/workflow/of/{id}/init` - Initialiser workflow OF

#### 🌱 **SojaWorkflowController** - Workflow SOJA complet
- `POST /api/soja/of/{id}/haccp` - Contrôle HACCP
- `POST /api/soja/of/{id}/analyse` - Analyse SOJA (1h30 max)
- `POST /api/soja/of/{id}/correction-avant` - Correction avant production
- `POST /api/soja/of/{id}/correction-apres` - Correction après production
- `POST /api/soja/of/{id}/okara` - Gestion okara
- `POST /api/soja/of/{id}/finalize` - Finalisation (double validation)
- `GET /api/soja/of/{id}/status` - Statut complet OF

#### 🌾 **CerealesWorkflowController** - Workflow CÉRÉALES complet
- `POST /api/cereales-workflow/of/{id}/haccp` - Contrôle HACCP
- `POST /api/cereales-workflow/of/{id}/cuve` - Création cuve
- `POST /api/cereales-workflow/of/{id}/decanteur` - Création décanteur
- `POST /api/cereales-workflow/of/{id}/correction-avant` - Correction avant
- `POST /api/cereales-workflow/of/{id}/correction-apres` - Correction après
- `POST /api/cereales-workflow/of/{id}/finalize` - Finalisation
- `GET /api/cereales-workflow/of/{id}/status` - Statut complet

#### 📄 **PDFController** - Génération de rapports
- `GET /api/pdf/of/{id}/generate` - PDF d'un OF
- `GET /api/pdf/of/{id}/preview` - Prévisualisation données
- `GET /api/pdf/production/{id}/reports` - Liste rapports production
- `GET /api/pdf/production/{id}/global-report` - Rapport global production

### 🎨 Templates PDF (Twig)
- `templates/pdf/soja_report.html.twig` - Rapport SOJA détaillé
- `templates/pdf/cereales_report.html.twig` - Rapport CÉRÉALES détaillé
- `templates/pdf/global_soja_report.html.twig` - Rapport global SOJA
- `templates/pdf/global_cereales_report.html.twig` - Rapport global CÉRÉALES

### 🔧 Services
- **PDFGeneratorService** : Génération PDF avec wkhtmltopdf
  - Calculs automatiques de moyennes, durées, conformité
  - Templates dynamiques par type de production
  - Conversion HTML → PDF avec mise en page industrielle

## 🚀 Installation & Configuration

### Prérequis
```bash
- PHP 8.1+
- Composer
- MySQL/MariaDB
- wkhtmltopdf (pour génération PDF)
```

### Installation
```bash
git clone [repository]
cd crea-analysis-recorder
composer install
```

### Configuration Base de Données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Configuration Twig (automatique)
```yaml
# config/packages/twig.yaml (créé automatiquement)
twig:
    default_path: '%kernel.project_dir%/templates'
    globals:
        app_name: 'CRÉA-ANALYSIS RECORDER'
```

## 📊 Tests

### Tests d'Intégration
```bash
php bin/phpunit tests/Integration/CompleteWorkflowTest.php
```

### Tests Unitaires
```bash
php bin/phpunit tests/Entity/
php bin/phpunit tests/Repository/
```

### Validation Entités
```bash
php bin/phpunit tests/Entity/AllEntitiesModernizedTest.php
```

## 🔄 Utilisation API

### Exemple Workflow SOJA Complet

#### 1. Créer Production
```bash
POST /api/workflow/productions
{
  "type": "soja",
  "date": "2024-01-15 08:00:00",
  "quantite_totale": 1000,
  "statut": "nouveau"
}
```

#### 2. Créer OF
```bash
POST /api/workflow/productions/1/ofs
{
  "numero": "OF-SOJA-001",
  "nature": "Lait de soja bio",
  "statut": "nouveau"
}
```

#### 3. HACCP
```bash
POST /api/soja/of/1/haccp
{
  "heure_controle": "2024-01-15 08:30:00",
  "point_controle": "Température pasteurisation",
  "temperature": 85.0,
  "duree": 30,
  "conformite": true
}
```

#### 4. Analyses (toutes les 1h30 max)
```bash
POST /api/soja/of/1/analyse
{
  "heure_analyse": "2024-01-15 09:00:00",
  "ph": 6.8,
  "brix": 12.5,
  "temperature": 75.0,
  "densite": 1.025,
  "couleur": "Blanc crème",
  "gout": "Doux",
  "conformite": true
}
```

#### 5. Corrections
```bash
POST /api/soja/of/1/correction-avant
{
  "type_correction": "Ajustement pH",
  "valeur_avant": 6.5,
  "valeur_apres": 6.8,
  "quantite_ajoutee": 0.5,
  "operateur": "Jean Dupont"
}
```

#### 6. Okara
```bash
POST /api/soja/of/1/okara
{
  "quantite": 150.0,
  "qualite": "Excellente",
  "humidite": 65.0,
  "destination": "Compostage",
  "operateur": "Marie Martin"
}
```

#### 7. Finalisation (Double Validation)
```bash
POST /api/soja/of/1/finalize
{
  "confirmation": true,
  "operateur_validation": "Chef Production",
  "commentaires": "Production conforme"
}
```

#### 8. Génération PDF
```bash
GET /api/pdf/of/1/generate
# Retourne le PDF complet avec tous les tableaux d'analyses
```

## 📈 Fonctionnalités Avancées

### ⏰ Validation Temporelle
- **SOJA** : Analyses obligatoires toutes les 1h30 maximum
- **CÉRÉALES** : Suivi en temps réel des cuves et décanteurs
- Alertes automatiques en cas de dépassement

### 🔍 Contrôles Qualité
- HACCP obligatoire avant toute production
- Validations de conformité à chaque étape
- Double validation pour finalisation

### 📊 Rapports Automatiques
- Calculs automatiques de moyennes (pH, Brix, températures)
- Durées de production calculées
- Taux de conformité globaux
- Rendements (Okara pour SOJA)

### 🎯 Statuts Dynamiques
- Suivi en temps réel de l'avancement
- Pourcentages de completion
- Prochaines étapes suggérées
- Workflow guidé par l'API

### 📋 Types de Rapports PDF
1. **Rapport OF individuel** : Toutes les données d'un OF
2. **Rapport global production** : Tous les OF d'une production
3. **Prévisualisation** : Données avant génération PDF
4. **Liste rapports** : Index des PDF disponibles

## 🔒 Sécurité & Validation

### Validations Métier
- Contrôles d'ordre des étapes
- Validation des données obligatoires
- Vérification des intervalles de temps
- Double validation pour finalisation

### Gestion d'Erreurs
- Messages d'erreur explicites
- Codes HTTP appropriés
- Logs détaillés des opérations
- Rollback automatique en cas d'erreur

## 🏭 Architecture Industrielle

### Traçabilité Complète
- Horodatage de toutes les opérations
- Identification des opérateurs
- Historique des modifications
- Audit trail complet

### Standards Industriels
- Respect des normes HACCP
- Conformité aux exigences qualité
- Documentation automatique
- Rapports d'inspection

### Évolutivité
- Architecture modulaire par type de production
- API RESTful extensible
- Templates PDF personnalisables
- Ajout facile de nouveaux types de production

---

## 🎯 Architecture Finale Validée

✅ **15 entités modernisées** avec PHP 8 attributes  
✅ **16 repositories** avec 200+ méthodes optimisées  
✅ **4 contrôleurs API** pour workflow complet  
✅ **Génération PDF** avec templates Twig professionnels  
✅ **Tests d'intégration** validant le workflow complet  
✅ **Double validation** pour finalisation  
✅ **Gestion temporelle** avec intervalles de 1h30 pour SOJA  

L'application est **prête pour la production industrielle** avec une architecture robuste, des workflows validés et une génération de rapports PDF professionnels.
