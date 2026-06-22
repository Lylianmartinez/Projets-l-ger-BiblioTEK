-- =====================================================================
-- Schéma de référence — Bibliotek (DDL)
-- Dérivé des migrations Laravel (database/migrations/).
-- Document de modélisation : la source de vérité reste les migrations.
-- Dialecte : MySQL 8 / MariaDB. Adapter les types pour SQLite/PostgreSQL.
-- =====================================================================

CREATE TABLE users (
    id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name              VARCHAR(255) NOT NULL,
    email             VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password          VARCHAR(255) NOT NULL,
    role              ENUM('usager','bibliothecaire') NOT NULL DEFAULT 'usager',
    is_active         BOOLEAN NOT NULL DEFAULT TRUE,
    remember_token    VARCHAR(100) NULL,
    created_at        TIMESTAMP NULL,
    updated_at        TIMESTAMP NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email)
);

CREATE TABLE auteurs (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom        VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);

CREATE TABLE categories (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    categorie  VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);

CREATE TABLE statuts (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    statut     VARCHAR(255) NOT NULL,  -- ex : disponible, emprunté, abîmé
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id)
);

CREATE TABLE livres (
    id         CHAR(36) NOT NULL,       -- UUID
    titre      VARCHAR(255) NOT NULL,
    auteur_id  BIGINT UNSIGNED NOT NULL,
    cover_url  VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT livres_auteur_id_foreign
        FOREIGN KEY (auteur_id) REFERENCES auteurs (id) ON DELETE CASCADE
);

CREATE TABLE livres_categories (
    livre_id     CHAR(36) NOT NULL,
    categorie_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (livre_id, categorie_id),
    CONSTRAINT lc_livre_id_foreign
        FOREIGN KEY (livre_id) REFERENCES livres (id) ON DELETE CASCADE,
    CONSTRAINT lc_categorie_id_foreign
        FOREIGN KEY (categorie_id) REFERENCES categories (id) ON DELETE CASCADE
);

CREATE TABLE exemplaires (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    livre_id        CHAR(36) NOT NULL,
    statut_id       BIGINT UNSIGNED NOT NULL,
    mise_en_service DATE NOT NULL,
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT exemplaires_livre_id_foreign
        FOREIGN KEY (livre_id) REFERENCES livres (id) ON DELETE CASCADE,
    CONSTRAINT exemplaires_statut_id_foreign
        FOREIGN KEY (statut_id) REFERENCES statuts (id)
);

CREATE TABLE emprunts (
    id                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id               BIGINT UNSIGNED NOT NULL,
    date_emprunt          DATE NOT NULL,
    date_retour_prevue    DATE NOT NULL,
    date_retour_effective DATE NULL,
    created_at            TIMESTAMP NULL,
    updated_at            TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT emprunts_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE emprunt_exemplaire (
    emprunt_id    BIGINT UNSIGNED NOT NULL,
    exemplaire_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (emprunt_id, exemplaire_id),
    CONSTRAINT ee_emprunt_id_foreign
        FOREIGN KEY (emprunt_id) REFERENCES emprunts (id) ON DELETE CASCADE,
    CONSTRAINT ee_exemplaire_id_foreign
        FOREIGN KEY (exemplaire_id) REFERENCES exemplaires (id) ON DELETE CASCADE
);
