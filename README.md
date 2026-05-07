# 🎓 Gestion des Élèves de Licence

> Projet réalisé par 4 étudiants de BTS1 — Cassandre, Hugo, Chloé et Elena

---

## Présentation

Ce projet est un site web de gestion des élèves de licence. Il permet de centraliser et d'administrer les données des étudiants via une interface web connectée à une base de données.

---

## Équipe

| Membre     |
|------------|
| Cassandre  |
| Hugo       |
| Chloé      |
| Elena      |

---

## Technologies utilisées

| Technologie | Usage |
|-------------|-------|
| HTML        | Structure des pages |
| CSS         | Mise en forme |
| PHP         | Logique serveur & connexion BDD |
| JavaScript  | Interactions côté client |

### Veilles technologiques réalisées
- Veille **Markdown**
- Veille **connexion base de données avec PHP**

---

## Méthodologie & Outils

- **Méthode AGILE** — sprints et issues
- Réunions hebdomadaires **chaque lundi** pour faire le point sur l'avancement
- Gestion des versions via **GitHub** (issues & sprints)
- Utilisation de **Git Bash** et **GitHub Desktop**

---

## Installation & Lancement

### Prérequis

- [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) installé sur votre machine

### Étapes

1. **Cloner le dépôt** sur votre machine locale

2. **Configurer la base de données**
   - Ouvrir **phpMyAdmin** (via XAMPP/WAMP)
   - Importer le fichier SQL situé dans le dossier `database/`

3. **Configurer les paramètres de connexion**
   - Ouvrir le fichier `parametres.php` à la racine du projet
   - Renseigner votre nom d'utilisateur et mot de passe MySQL :
     ```php
     $user = "votre_utilisateur";
     $password = "votre_mot_de_passe";
     ```

4. **Lancer le projet**
   - Démarrer **Apache** et **MySQL** depuis XAMPP/WAMP
   - Accéder à l'URL suivante dans votre navigateur :
     ```
     http://localhost/EnseignementSuperieur/public
     ```

---

## Arborescence du projet

```
projet/
│
├── database/           # Fichier SQL de la base de données
│
├── public/             # Fichiers accessibles depuis le navigateur
│   ├── *.php           # Pages PHP
│   ├── *.css           # Feuilles de style
│   └── images/         # Ressources visuelles
│
├── ressources/         # Documentation & veilles technologiques
│
└── parametres.php      # Configuration de la connexion BDD
```

---

## Conventions de nommage

Les classes CSS sont écrites :
- En **anglais**
- En **minuscules**
- Avec un **tiret `-`** comme séparateur entre les mots

> Exemple : `.student-list`, `.form-container`

---

## Ressources & outils utilisés

| Outil            | Usage |
|------------------|-------|
| Figma            | Maquettage de l'interface |
| Word             | Documentation |
| Excel            | Suivi du projet |
| XAMPP            | Environnement de développement local |
| GitHub Desktop   | Interface graphique Git |
| GitHub           | Hébergement du dépôt & gestion des sprints |
| Git Bash         | Commandes Git en ligne de commande |
