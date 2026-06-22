# 📚 BiblioTEK

Application web de **gestion de bibliothèque** développée avec Laravel 12.
Elle permet aux usagers de consulter le catalogue, d'emprunter et de rendre des
ouvrages, et aux bibliothécaires de gérer les exemplaires, les comptes et la base
de données depuis un back-office dédié.

🔗 **Démo en ligne :** <https://lylian.xcsoftworks.com/bibliotek/>

---

## ✨ Fonctionnalités

### Visiteurs
- Recherche dans le catalogue par titre / auteur
- Inscription et connexion (avec limitation de débit anti-bruteforce)

### Usagers connectés
- Consultation de leur profil et de l'historique d'emprunts
- Emprunt d'un ou plusieurs exemplaires
- Retour d'ouvrages

### Bibliothécaires (back-office `/bo`)
- Gestion des **exemplaires** (CRUD : ajout, modification, suppression)
- Gestion des **usagers** et de leurs **comptes** (consultation, modification,
  suspension, suppression)
- Validation des retours
- **Tableau de bord base de données** (`/bo/database`) façon phpLiteAdmin :
  liste des tables, parcours paginé et console SQL (lecture + écriture) —
  protégé par l'authentification, sans mot de passe statique exposé

---

## 🛠️ Stack technique

| Couche | Technologie |
|--------|-------------|
| Backend | PHP 8.2+ · [Laravel 12](https://laravel.com) |
| Base de données | SQLite (par défaut) — compatible MySQL/MariaDB/PostgreSQL |
| Frontend | Blade · Tailwind CSS 4 · Vite 7 |
| Tests | PHPUnit 11 |
| Outillage | Laravel Pint (style), Pail (logs), Sail (Docker) |

---

## 🗂️ Modèle de données

Le schéma complet est documenté dans [`docs/database/`](docs/database/) sous
plusieurs formats (PlantUML, Mermaid, DBML, DDL SQL). Vue d'ensemble :

```
Auteur ──< Livre >──< Categorie          (un livre a un auteur, plusieurs catégories)
              │
              └──< Exemplaire >── Statut  (copies physiques, chacune dans un état)
                       │
User ──< Emprunt >─────┘                  (un emprunt porte sur un ou plusieurs exemplaires)
```

| Entité | Rôle |
|--------|------|
| `users` | Comptes — rôle `usager` ou `bibliothecaire`, indicateur `is_active` |
| `auteurs` | Auteurs des livres |
| `categories` | Genres / classifications |
| `livres` | Œuvres du catalogue (clé primaire **UUID**) |
| `exemplaires` | Copies physiques d'un livre, chacune avec un statut |
| `statuts` | États possibles (disponible, emprunté, abîmé…) |
| `emprunts` | Opérations de prêt rattachées à un usager |

> 📖 Diagrammes : [`schema.puml`](docs/database/schema.puml) ·
> [`class-diagram.puml`](docs/database/class-diagram.puml) ·
> [`schema.mermaid.md`](docs/database/schema.mermaid.md) ·
> [`schema.dbml`](docs/database/schema.dbml) ·
> [`schema.sql`](docs/database/schema.sql)

---

## 🚀 Installation

### Prérequis
- PHP **8.2+** avec les extensions habituelles de Laravel (`pdo_sqlite`, `mbstring`, …)
- [Composer](https://getcomposer.org)
- [Node.js](https://nodejs.org) **18+** et npm

### Étapes

```bash
# 1. Cloner le dépôt
git clone <url-du-repo> bibliotek
cd bibliotek

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Créer la base SQLite
touch database/database.sqlite
# (vérifier dans .env : DB_CONNECTION=sqlite)

# 5. Migrer et peupler la base
php artisan migrate --seed

# 6. Compiler les assets
npm run build
```

### Lancer en développement

```bash
# Serveur Laravel + queue + logs + Vite, en une commande
composer dev

# …ou simplement
php artisan serve
```

L'application est alors disponible sur <http://localhost:8000>.

---

## 👤 Comptes de démonstration

Après `php artisan migrate --seed` :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| **Bibliothécaire** | `bibliothecaire@bibliotek.fr` | `Bibliotek2026!` |
| Usager | `lucas@example.fr` (et `emma@`, `hugo@`, `lea@`…) | `password` |
| Usager suspendu | `suspendu@example.fr` | `password` |

> ⚠️ Ces identifiants ne servent qu'en développement / démo. **Changez-les
> avant toute mise en production.**

---

## 🧪 Tests

```bash
php artisan test
```

Suite de tests fonctionnels et unitaires couvrant l'authentification, la
recherche, les emprunts, les retours et le back-office des exemplaires
(`tests/Feature`, `tests/Unit`).

---

## 📁 Structure du projet

```
app/
├── Http/Controllers/
│   ├── Auth/            # Inscription, connexion
│   ├── BackOffice/      # Exemplaires, profils, base de données (bibliothécaire)
│   ├── Admin/           # Gestion des comptes usagers
│   ├── RechercheController.php
│   ├── EmpruntController.php
│   └── RetourController.php
├── Http/Middleware/CheckRole.php   # Contrôle d'accès par rôle
└── Models/             # Auteur, Categorie, Livre, Exemplaire, Statut, Emprunt, User
database/
├── migrations/         # Schéma de la base
├── seeders/            # Données de démonstration
└── factories/
docs/database/          # Modélisation (PlantUML, Mermaid, DBML, SQL)
resources/views/        # Templates Blade
routes/web.php          # Routes de l'application
tests/                  # Tests PHPUnit
```

---

## 🔐 Sécurité

- Accès au back-office restreint via le middleware `role:bibliothecaire`.
- Limitation de débit sur l'inscription et la connexion.
- Le tableau de bord base de données est servi **par Laravel et protégé par
  l'authentification** — il n'expose aucun outil tiers ni mot de passe statique
  dans la racine web.

---

## 📄 Licence

Distribué sous licence [MIT](https://opensource.org/licenses/MIT).
