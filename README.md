# 🎉 Shatek Event

**Shatek Event** est une application de planification et de gestion d’événements. Elle permet aux utilisateurs, organisateurs et partenaires de gérer tous les aspects liés à un événement à travers une plateforme complète et modulaire.

## 🧩 Modules Fonctionnels

Le projet est divisé en 6 modules principaux :

1. **👤 Gestion des utilisateurs**
   - Authentification et autorisation
   - Profils des utilisateurs (organisateurs, participants, admins,sponsors)
   - Gestion des rôles et permissions

2. **📣 Gestion des réclamations**
   - Soumission de réclamations par les utilisateurs
   - Traitement et suivi des réclamations
   - Notifications liées aux réclamations

3. **📅 Gestion des événements**
   - Création et modification d’événements
   - Affichage des événements par catégorie, date, lieu, etc.
   - Inscriptions et participation

4. **🛎️ Gestion des services**
   - Ajout de services liés aux événements : traiteurs, sécurité, décoration, **transport**, etc.
   - Réservation et gestion des prestataires/fournisseurs de services
   - Suivi logistique du transport (horaires, points de départ/arrivée)

5. **📂 Gestion des supports**
   - Importation et téléchargement de fichiers multimédias liés à chaque événement (PDF, vidéos, images…)
   - Conversion des supports en fichiers audio


6. **💰 Gestion du sponsoring**
   - Ajout et suivi des sponsors
   - Gestion des contrats et contreparties

## 🔧 Technologies Utilisées

- 🔙 Backend : Symfony (PHP)
- 🎨 Frontend : Symfony (Twig, HTML/CSS, JavaScript)
- 🛢️ Base de données : MySQL
- 🧰 Autres :
  - API Google Drive (gestion des fichiers)
  - API de mailing (notifications par email)
  - API de géolocalisation (Google Maps)
  - Conversion de fichiers en audio
  - Filtrage automatique de contenu inapproprié (badwords)
  - Reconnaissance faciale (authentification ou vérification)
  - Intégration Cloud (stockage ou services tiers)
  - Système de notifications locales
   - API reCAPTCHA (protection contre les bots)


## 🚀 Lancement du projet

```bash
# Cloner le projet
git clone https://github.com/ton-utilisateur/shatek-event.git

# Accéder au dossier
cd shatek-event


