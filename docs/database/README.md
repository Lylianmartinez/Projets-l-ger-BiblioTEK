# Modélisation de la base de données — Bibliotek

Documentation du modèle de données de l'application Bibliotek (système de gestion
de bibliothèque sous Laravel). La **source de vérité** reste les migrations
(`database/migrations/`) ; les fichiers ci-dessous en sont une représentation
synchronisée, déclinée dans plusieurs frameworks de modélisation.

## Fichiers

| Fichier | Format | Outil de rendu |
|---------|--------|----------------|
| [`schema.puml`](schema.puml) | PlantUML — diagramme entité-association | [PlantUML](https://www.plantuml.com/plantuml), extension VS Code « PlantUML » |
| [`class-diagram.puml`](class-diagram.puml) | PlantUML — diagramme de classes (modèles Eloquent) | idem |
| [`schema.mermaid.md`](schema.mermaid.md) | Mermaid — ER diagram | GitHub/GitLab (natif), [mermaid.live](https://mermaid.live) |
| [`schema.dbml`](schema.dbml) | DBML | [dbdiagram.io](https://dbdiagram.io), [dbdocs.io](https://dbdocs.io) |
| [`schema.sql`](schema.sql) | DDL SQL (MySQL/MariaDB) | tout client SQL |

### Rendu rapide en images (PlantUML)

```bash
# nécessite Java + plantuml.jar (ou le paquet « plantuml »)
plantuml docs/database/schema.puml docs/database/class-diagram.puml
# -> génère schema.png et class-diagram.png
```

## Vue d'ensemble du domaine

```
Auteur ──< Livre >──< Categorie          (un livre a un auteur, plusieurs catégories)
              │
              └──< Exemplaire >── Statut  (copies physiques, chacune dans un état)
                       │
User ──< Emprunt >─────┘                  (un emprunt porte sur un ou plusieurs exemplaires)
```

## Entités

- **users** — comptes. `role` distingue `usager` et `bibliothecaire` ; `is_active`
  permet de désactiver un compte sans le supprimer.
- **auteurs** — auteurs des livres.
- **categories** — genres / classifications.
- **statuts** — états possibles d'un exemplaire (ex. : *disponible*, *emprunté*, *abîmé*).
- **livres** — œuvres du catalogue. **Clé primaire UUID**. Rattaché à un auteur,
  champ `cover_url` optionnel pour la couverture.
- **exemplaires** — copies physiques d'un livre ; chacune a un statut et une date
  de mise en service.
- **emprunts** — opérations de prêt rattachées à un usager, avec dates de prêt,
  de retour prévue et effective (nullable tant que non rendu).

## Associations

| Relation | Cardinalité | Table |
|----------|-------------|-------|
| Auteur → Livre | 1 — N | `livres.auteur_id` |
| Livre ↔ Categorie | N — N | `livres_categories` |
| Livre → Exemplaire | 1 — N | `exemplaires.livre_id` |
| Statut → Exemplaire | 1 — N | `exemplaires.statut_id` |
| User → Emprunt | 1 — N | `emprunts.user_id` |
| Emprunt ↔ Exemplaire | N — N | `emprunt_exemplaire` |

## Notes de conception

- Les `livres` utilisent une clé **UUID** (les autres tables un `BIGINT`
  auto-incrémenté) ; les FK pointant vers `livres` sont donc de type UUID.
- Suppressions en cascade (`ON DELETE CASCADE`) sur toutes les FK **sauf**
  `exemplaires.statut_id` : on ne supprime pas un statut référencé.
- Deux tables pivots pures (sans `id` ni timestamps) à PK composite :
  `livres_categories` et `emprunt_exemplaire`.

> Pour régénérer la documentation après une évolution du schéma, mettez à jour
> ces fichiers en cohérence avec les nouvelles migrations.
