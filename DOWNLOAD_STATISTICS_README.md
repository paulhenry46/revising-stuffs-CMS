# Système de Statistiques de Téléchargements

## Vue d'ensemble

Ce système remplace l'ancien système basé sur un compteur simple par un système événementiel qui enregistre chaque téléchargement individuellement. Cela permet des analyses statistiques détaillées et des requêtes temporelles avancées.

## Architecture

### 1. Migration de la base de données

**Table `downloads`** (`2026_01_01_100000_create_downloads_table.php`)
- `id`: Identifiant unique du téléchargement
- `post_id`: Référence au post (avec suppression en cascade)
- `user_id`: Référence à l'utilisateur (nullable, null on delete)
- `downloaded_at`: Timestamp du téléchargement
- Index sur `post_id`, `downloaded_at`, et combiné `(post_id, downloaded_at)` pour optimiser les requêtes

**Colonne `downloads_total` sur la table `posts`** (`2026_01_01_100001_add_downloads_total_to_posts_table.php`)
- Compteur global pour un accès rapide au nombre total de téléchargements

### 2. Modèles

**`App\Models\Download`**
- Gère les enregistrements individuels de téléchargements
- Relations: `post()`, `user()`
- Cast automatique de `downloaded_at` en datetime
- Pas de timestamps Laravel (utilise uniquement `downloaded_at`)

**`App\Models\Post`** (mis à jour)
- Nouvelle relation `downloads()`: retourne tous les événements de téléchargement
- Méthode `incrementDownloads()`: incrémente le compteur global
- Méthode `downloadsFromFiles()`: ancienne méthode pour compatibilité

**`App\Models\User`** (mis à jour)
- Nouvelle relation `downloads()`: retourne tous les téléchargements de l'utilisateur

### 3. Services

**`App\Services\DownloadService`**

Méthode principale:
```php
public function recordDownload(Post $post, ?User $user = null): Download
```
- Enregistre un événement de téléchargement
- Met à jour le compteur global du post
- Utilise une transaction pour garantir la cohérence
- Supporte les téléchargements anonymes (user = null)

**`App\Services\DownloadStatistics`**

Méthodes de requêtes statistiques:

1. `getDownloadsByMonth(Post $post)`: Téléchargements par mois pour un post
2. `getDownloadsByMonthAndType(string $type)`: Téléchargements par mois et type de post
3. `getUserDownloadsByMonth(User $user)`: Téléchargements d'un utilisateur par mois
4. `getTopPostsForMonth(int $year, int $month, int $limit = 10)`: Top posts du mois
5. `getTotalDownloadsForPost(Post $post)`: Total de téléchargements pour un post
6. `getDownloadsForPostByMonth(Post $post, int $year, int $month)`: Téléchargements d'un post pour un mois
7. `getDownloadsByDay(int $year, int $month)`: Tendances par jour
8. `getDownloadsByType()`: Répartition par type de post

Toutes les requêtes sont optimisées avec:
- `whereYear()` et `whereMonth()` pour les filtres temporels
- `selectRaw()` pour les agrégations
- `groupBy()` pour les regroupements
- Index de base de données appropriés

### 4. Intégration

**`App\Jobs\IncrementDownload_count`** (mis à jour)
- Conserve l'ancien système de compteur de fichiers pour compatibilité
- Ajoute l'enregistrement dans le nouveau système événementiel
- Utilise `DownloadService::recordDownload()`

**`App\Http\Controllers\StorageController`**
- Inchangé, utilise toujours `IncrementDownload_count`
- Le job gère maintenant les deux systèmes

## Utilisation

### Enregistrer un téléchargement

```php
use App\Services\DownloadService;

$downloadService = app(DownloadService::class);

// Avec utilisateur authentifié
$download = $downloadService->recordDownload($post, auth()->user());

// Téléchargement anonyme
$download = $downloadService->recordDownload($post, null);
```

### Obtenir des statistiques

```php
use App\Services\DownloadStatistics;

$stats = app(DownloadStatistics::class);

// Total pour un post
$total = $stats->getTotalDownloadsForPost($post);

// Par mois pour un post
$monthlyStats = $stats->getDownloadsByMonth($post);

// Top posts de janvier 2026
$topPosts = $stats->getTopPostsForMonth(2026, 1, 10);

// Téléchargements d'un utilisateur par mois
$userStats = $stats->getUserDownloadsByMonth($user);
```

## Tests

### Tests unitaires
- `tests/Unit/DownloadTest.php`: Tests du modèle Download

### Tests d'intégration
- `tests/Feature/DownloadServiceTest.php`: Tests du service d'enregistrement
- `tests/Feature/DownloadStatisticsTest.php`: Tests des statistiques

### Factories
- `database/factories/DownloadFactory.php`
- `database/factories/PostFactory.php`

## Migration des données existantes

Si vous avez des données existantes dans le compteur `downloads` des fichiers, vous pouvez créer une migration de données pour peupler la nouvelle table `downloads` avec des enregistrements historiques.

Exemple de commande artisan pour migrer les données:
```php
// À exécuter une fois après le déploiement
php artisan migrate
```

## Optimisation

Les index suivants sont créés automatiquement pour optimiser les requêtes:
- Index sur `post_id`
- Index sur `downloaded_at`
- Index composite sur `(post_id, downloaded_at)`

Ces index accélèrent considérablement:
- Les requêtes par post
- Les requêtes temporelles
- Les statistiques combinant post et date

## Compatibilité

Le système est rétrocompatible:
- L'ancien compteur de fichiers continue de fonctionner
- La méthode `downloadsFromFiles()` est disponible pour les anciens usages
- Le nouveau système s'ajoute sans casser l'existant
