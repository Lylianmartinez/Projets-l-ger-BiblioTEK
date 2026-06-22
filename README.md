# Tic'n Go — Billetterie Desktop

Application de billetterie en client lourd développée avec **JavaFX** et **Spring Boot**.  
Elle permet à des administrateurs de gérer des spectacles, des lieux et des tickets, et à des clients d'acheter et consulter leurs billets.

---

## Fonctionnalités

### Espace Admin
- Tableau de bord avec statistiques (spectacles, clients, tickets, lieux)
- Gestion des spectacles (ajout, modification, suppression)
- Gestion des lieux avec suivi des capacités
- Gestion des clients
- Gestion des tickets : validation, annulation, remboursement
- Export PDF des tickets avec **QR code** intégré
- Notifications email lors d'annulations *(optionnel)*

### Espace Client
- Parcourir et rechercher des spectacles disponibles
- Réserver des tickets (choix de la séance, catégorie tarifaire, quantité)
- Consulter ses réservations et afficher le QR code de chaque ticket
- Gérer son profil (informations personnelles, mot de passe)

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Langage | Java 21 |
| Interface graphique | JavaFX 21 + FXML |
| Framework applicatif | Spring Boot 3.5 |
| Persistance | Spring Data JPA + Hibernate |
| Base de données | MySQL 8+ |
| Sécurité | Spring Security Crypto (BCrypt) |
| Génération PDF | OpenPDF 1.3.30 |
| QR Code | ZXing 3.5.2 |
| Email | Spring Mail (SMTP Gmail) |
| Build | Maven (wrapper inclus) |
| Utilitaires | Lombok |

---

## Prérequis

- **JDK 21** — [Télécharger](https://adoptium.net/)
- **MySQL 8+** — [Télécharger](https://dev.mysql.com/downloads/)
- Maven n'est **pas** nécessaire à installer (le wrapper `mvnw` est inclus)

---

## Installation & lancement

### 1. Cloner le dépôt

```bash
git clone https://github.com/ton-utilisateur/ticngo.git
cd ticngo
```

### 2. Configurer la base de données

Démarrer MySQL puis vérifier le fichier `src/main/resources/application.properties` :

```properties
spring.datasource.url=jdbc:mysql://localhost:3306/ticngo?createDatabaseIfNotExist=true
spring.datasource.username=root
spring.datasource.password=        # ← ton mot de passe MySQL ici
```

> La base de données `ticngo` est **créée automatiquement** au premier lancement.

### 3. Lancer l'application

```powershell
# Windows
.\mvnw.cmd javafx:run
```

```bash
# Linux / macOS
./mvnw javafx:run
```

> La première exécution peut prendre quelques minutes (téléchargement des dépendances Maven).

---

## Structure du projet

```
src/main/java/com/ticngo/ticngo/
├── Main.java                    # Point d'entrée JavaFX
├── TicNGoApp.java               # Application JavaFX
├── TicngoApplication.java       # Point d'entrée Spring Boot
├── config/                      # Configuration (BCrypt, données initiales)
├── entity/                      # Entités JPA (Show, Session, Ticket, Client…)
├── dto/                         # Formulaires / objets de transfert
├── repository/                  # Accès base de données (Spring Data)
├── service/                     # Logique métier
└── javafx/
    ├── LoginController.java     # Authentification
    ├── admin/                   # Interface administrateur
    └── client/                  # Interface client

src/main/resources/
├── application.properties       # Configuration générale
├── fxml/                        # Layouts JavaFX (login, admin, client)
└── css/                         # Styles de l'interface
```

---

## Modèle de données

```
Venue ──< Show ──< Session ──< Ticket >── Client
                        └──< PricingCategory
Show >── Tag
```

| Entité | Description |
|--------|-------------|
| `Show` | Spectacle (titre, affiche, durée, langue, description) |
| `Session` | Séance d'un spectacle (date, heure, places disponibles) |
| `Ticket` | Billet individuel avec numéro unique `TNG-XXXXXXXX` |
| `Client` | Utilisateur final (email, mot de passe hashé) |
| `Admin` | Administrateur (rôle ADMIN ou SUPER_ADMIN) |
| `Venue` | Lieu de représentation (nom, adresse, capacité) |
| `PricingCategory` | Catégorie tarifaire (plein tarif, réduit, etc.) |
| `Tag` | Étiquettes de classification des spectacles |

---

## Configuration email *(optionnelle)*

Pour activer les notifications email lors d'annulations de tickets :

```properties
ticngo.mail.enabled=true
spring.mail.username=votre-email@gmail.com
spring.mail.password=votre-mot-de-passe-application   # mot de passe d'app Gmail
```

> Désactivé par défaut (`ticngo.mail.enabled=false`).

---

## Résolution des erreurs courantes

| Erreur | Solution |
|--------|----------|
| `Communications link failure` | MySQL n'est pas démarré |
| `Access denied for user 'root'` | Mauvais mot de passe dans `application.properties` |
| `JAVA_HOME not set` | Installer JDK 21 et configurer la variable d'environnement |
| La fenêtre JavaFX ne s'ouvre pas | Vérifier que le JDK 21 est bien installé |

---

## Licence

Ce projet est réalisé dans un cadre académique / personnel.

