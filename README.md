
# Mini Application De Réservation de Bureaux

Application permettant à un utilisateur de réserver un bureau et de consulter la liste de ses réservations.

## Technologies utilisées

- **Backend**: Laravel 12, JWT Authentication (tymon/jwt-auth), Swagger (L5-Swagger) pour la documentation API, RESTful API
- **Frontend**: NuxtJS 3, Pinia, fetch, TypeScript, Bootstrap
- **Base de données**: MySQL
- **Autres outils**: Insomnia, Swagger UI, Git, Github, Git bash, Vscode, XAMPP
---

## Structure du projet

/backend      -> Laravel 12 API
/frontend     -> NuxtJS 3 frontend
README.md     -> Details du projet

---

## Fonctionnalités

-  Connexion d'un utilisateur
-  Déconnexion d'un utilisateur
-  Réservation d'un bureau si l'utilisateur connecté 
-  Liste des réservations de l'utilisateur connecté

---

## Installation & Lancement

### Prérequis

- PHP = 8.2.12
- Node.js = 22.0.0
- Composer 2.7.6
- MySQL ou MariaDB
- NPM ou npx

### Installation Backend (Laravel)

Commandes backend à taper:
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve

### Installation Frontend (NuxtJS 3)

Commandes frontend à taper:
cd frontend
npm install
npm run dev

---

## Authentification

Le backend utilise **auth:api** pour sécuriser les endpoints via JWT.

### Exemple de login API

POST /api/login
Content-Type: application/json

{
  "email": "dev@gmail.com",
  "password": "password"
}

### En-tête Authorization

Une fois connecté, passez le token JWT d'authentification dans les requetes protégées :

Authorization: Bearer {token}

---

## Documentation API (Swagger)

Swagger est accessible via :

http://localhost:8000/api/documentation

### Ajouter l'autorisation via le bouton "Authorize" :

Ajoutez le token JWT en cliquant sur le bouton Authorize dans Swagger UI :

Bearer {token}

---

## Exemples d'Endpoints

### Créer une réservation

POST /api/reservation
Authorization: Bearer {token}
Content-Type: application/json

{
  "bureau_id": 1,
  "date_debut": "2025-06-17T15:00:00",
  "date_fin": "2025-07-20T16:05:00"
}

### 📄 Lister les réservations de l'utilisateur

GET /api/users/{id}/reservations
Authorization: Bearer {token}

---

## Tests

Pour exécuter les tests backend :

Tous les test: 
php artisan test

Uniquement les tests unitaires

php artisan test --testsuite=Unit

Uniquement les test d'integration

php artisan test --testsuite=Feature


---

## Auteur

Projet fonctionnel développé par MBIAPO NZEPA Idriss Cabrel

