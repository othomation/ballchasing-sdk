# Ballchasing Laravel SDK - Guide d'utilisation

## Obtenir un Token API

Il existe deux façons d'obtenir un token API valide :

### Méthode 1 : Interface Web (Recommandée)

1. Allez sur [https://ballchasing.com/upload](https://ballchasing.com/upload)
2. Connectez-vous via Steam (obligatoire)
3. Utilisez l'interface web pour générer un nouveau token API
4. Copiez le token généré dans votre fichier `.env`

### Méthode 2 : Génération Programmatique de Token

Si vous devez générer des tokens de manière programmatique (ex: pour l'automatisation) :

1. D'abord, connectez-vous via Steam sur [https://ballchasing.com](https://ballchasing.com)
2. Récupérez le cookie `ballchasing` depuis votre navigateur
3. Effectuez une requête POST pour générer un token :

```bash
curl -X POST https://ballchasing.com/user/token \
  -H "Cookie: ballchasing=<valeur_de_votre_cookie>"
```

**Note :** La méthode par cookie est principalement destinée aux cas d'usage avancés. Pour la plupart des applications, utiliser l'interface web est plus simple et plus sécurisé.

## Utilisation de Base

Injectez le client dans vos classes :

```php
use Lucie\BallchasingLaravel\Contracts\BallchasingClientInterface;

class ReplayController extends Controller
{
    public function __construct(
        private BallchasingClientInterface $ballchasing
    ) {}

    public function index()
    {
        $replays = $this->ballchasing->getReplays([
            'count' => 10,
            'sort-by' => 'created',
            'sort-dir' => 'desc'
        ]);

        return view('replays.index', compact('replays'));
    }
}
```

## Méthodes Disponibles

### Replays

```php
// Récupérer des replays avec des filtres optionnels
$replays = $ballchasing->getReplays([
    'title' => 'Grande Finale',
    'playlist' => 'ranked-standard',
    'count' => 50
]);

// Récupérer un replay spécifique
$replay = $ballchasing->getReplay('replay-id');

// Uploader un fichier replay
$replay = $ballchasing->uploadReplay('/chemin/vers/replay.replay', [
    'title' => 'Mon Super Replay',
    'visibility' => 'public'
]);

// Mettre à jour les métadonnées d'un replay
$replay = $ballchasing->updateReplay('replay-id', [
    'title' => 'Titre Mis à Jour'
]);

// Supprimer un replay
$ballchasing->deleteReplay('replay-id');
```

### Groupes

```php
// Récupérer des groupes
$groups = $ballchasing->getGroups(['count' => 10]);

// Récupérer un groupe spécifique
$group = $ballchasing->getGroup('group-id');

// Créer un nouveau groupe
$group = $ballchasing->createGroup([
    'name' => 'Replays de Tournoi',
    'type' => 'regular'
]);

// Mettre à jour un groupe
$group = $ballchasing->updateGroup('group-id', [
    'name' => 'Nom de Groupe Mis à Jour'
]);

// Supprimer un groupe
$ballchasing->deleteGroup('group-id');
```

### Cartes

```php
// Récupérer toutes les cartes disponibles
$maps = $ballchasing->getMaps();
// Retourne: ['stadium_p' => 'DFH Stadium', 'park_p' => 'Beckwith Park', ...]
```

## Objets de Transfert de Données (DTOs)

Le package utilise des DTOs pour la sécurité des types et un meilleur support IDE :

```php
$replays = $ballchasing->getReplays();

foreach ($replays->replays as $replay) {
    echo $replay->id;        // string
    echo $replay->title;     // ?string (peut être null)
    echo $replay->created;   // string (date ISO)
    echo $replay->duration;  // ?int (secondes)
    echo $replay->mapCode;   // ?string
}

echo $replays->count; // Nombre total de replays
```

## Gestion des Erreurs

Le package lance des exceptions personnalisées pour différents scénarios d'erreur :

```php
use Lucie\BallchasingLaravel\Exceptions\BallchasingException;

try {
    $replay = $ballchasing->getReplay('id-invalide');
} catch (BallchasingException $e) {
    // Gérer les erreurs de l'API
    echo $e->getMessage();
    echo $e->getCode(); // Code de statut HTTP
}
```

## Options de Configuration

Le fichier `config/ballchasing.php` vous permet de configurer :

```php
return [
    'api_key' => env('BALLCHASING_API_KEY'),
    'base_url' => env('BALLCHASING_BASE_URL', 'https://ballchasing.com/api'),
    'timeout' => env('BALLCHASING_TIMEOUT', 30),
    'retry_attempts' => env('BALLCHASING_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('BALLCHASING_RETRY_DELAY', 500), // millisecondes
];
```

## Fichiers d'Environnement

- `.env.example` - Exemple de configuration pour les applications utilisant ce package
- `.env.testing` - Modèle pour les tests d'intégration
- `.env` - Votre configuration de test locale (ignoré par git)

## Cas d'Utilisation Courants

### Gestion de Tournoi

```php
// Créer un groupe de tournoi
$tournament = $ballchasing->createGroup([
    'name' => 'Championnat du Monde RLCS 2024',
    'type' => 'tournament'
]);

// Uploader des replays vers le tournoi
$replay = $ballchasing->uploadReplay('/chemin/vers/finale.replay', [
    'title' => 'Grande Finale - Match 7',
    'group' => $tournament->id,
    'visibility' => 'public'
]);
```

### Statistiques de Joueur

```php
// Récupérer les replays pour un joueur spécifique
$playerReplays = $ballchasing->getReplays([
    'player-name' => 'Jstn.',
    'count' => 100,
    'sort-by' => 'created',
    'sort-dir' => 'desc'
]);

foreach ($playerReplays->replays as $replay) {
    echo "Match: {$replay->title} le " . date('d/m/Y', strtotime($replay->created));
}
```

### Analyse de Cartes

```php
// Récupérer toutes les cartes disponibles
$maps = $ballchasing->getMaps();

// Filtrer les replays par carte spécifique
$stadiumReplays = $ballchasing->getReplays([
    'map' => 'stadium_p', // DFH Stadium
    'count' => 50
]);
```