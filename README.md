# Filament Phone Number

Un plugin personnalisé pour Filament PHP qui ajoute un champ de saisie de numéro de téléphone avec validation et formatage international, ainsi qu'une colonne pour afficher des numéros de téléphone dans les tables.

## Fonctionnalités

- Intégration transparente avec Filament Admin et Filament Forms
- Validation et formatage des numéros de téléphone internationaux
- Support pour les indicatifs et drapeaux de pays
- Masques de saisie personnalisables par pays
- Conversion automatique vers le format E164 pour le stockage
- Affichage formaté dans les colonnes de table Filament

## Installation

Vous pouvez installer le package via composer:

```bash
composer require macymed-org/filament-phone-number
```

Publiez ensuite les ressources si nécessaire:

```bash
# Pour publier la configuration
php artisan vendor:publish --tag="filament-macymed-phone-number-config"

# Pour publier les vues
php artisan vendor:publish --tag="filament-macymed-phone-number-views"
```

## Configuration

Après l'installation, vous pouvez configurer le plugin en modifiant le fichier de configuration publié:

```php
// config/filament-macymed-phone-number.php

return [
    'default_country' => 'FR',
    'show_flags' => true,
    'save_as_e164' => true,
    'default_format' => 'NATIONAL',
];
```

## Utilisation

### En tant que champ de formulaire

```php
use Macymed\FilamentPhoneNumber\Components\PhoneNumberInput;

// Dans votre méthode form()
PhoneNumberInput::make('phone_number')
    ->label('Numéro de téléphone')
    ->required()
    ->defaultCountry('FR')         // Pays par défaut (FR, US, etc.)
    ->showFlags(true)              // Afficher les drapeaux des pays
    ->saveAsE164(true)             // Sauvegarder au format E164
    ->countries([                  // Personnaliser la liste des pays disponibles
        'FR' => ['name' => 'France', 'prefix' => '+33'],
        'BE' => ['name' => 'Belgique', 'prefix' => '+32'],
        // ...
    ])
    ->masks([                      // Personnaliser les masques par pays
        'FR' => '## ## ## ## ##',
        'BE' => '### ### ###',
        // ...
    ]);
```

### En tant que colonne dans une table

```php
use Macymed\FilamentPhoneNumber\Components\PhoneNumberColumn;

// Dans votre méthode table()
PhoneNumberColumn::make('phone_number')
    ->label('Téléphone')
    ->searchable()
    ->sortable()
    ->showFlags(true)              // Afficher les drapeaux des pays
    ->format('NATIONAL')           // Format d'affichage: NATIONAL, INTERNATIONAL, E164
    ->copyable();                  // Permettre la copie du numéro
```

## Validation

Pour valider un numéro de téléphone, vous pouvez utiliser la règle `phone` fournie par le package `propaganistas/laravel-phone` dans vos requêtes:

```php
use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\Rules\Phone;

class ContactRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone_number' => [
                'required',
                (new Phone)->country(['FR', 'BE', 'CH']), // Pays autorisés
            ],
        ];
    }
}
```

## Personnalisation

### Personnalisation des drapeaux et pays

Le plugin utilise les helpers `CountryHelper` et `PhoneNumberHelper` pour gérer les pays et les numéros. Vous pouvez étendre ces classes pour personnaliser le comportement.

```php
use Macymed\FilamentPhoneNumber\Helpers\CountryHelper;

// Pour obtenir tous les pays disponibles
$countries = CountryHelper::getAllCountries();

// Pour obtenir les masques par défaut
$masks = CountryHelper::getDefaultMasks();

// Pour obtenir le drapeau d'un pays
$flag = CountryHelper::getCountryFlag('FR');
```

### Manipulation des numéros de téléphone

```php
use Macymed\FilamentPhoneNumber\Helpers\PhoneNumberHelper;

// Convertir un numéro en format E164
$e164 = PhoneNumberHelper::formatToE164('01 23 45 67 89', 'FR');

// Analyser un numéro au format E164
$info = PhoneNumberHelper::parseE164('+33123456789');
// Retourne: ['country_code' => 'FR', 'national_number' => '01 23 45 67 89', 'e164' => '+33123456789']

// Appliquer un masque à un numéro
$formatted = PhoneNumberHelper::applyMask('0123456789', '## ## ## ## ##');
// Résultat: '01 23 45 67 89'
```

## Mise à jour depuis une version précédente

Si vous mettiez à jour ce package et utilisiez précédemment une version qui faisait référence à `filament-phone-number` dans votre code, vous devrez mettre à jour vos références vers les vues dans vos composants :

```php
// Ancien code
protected string $view = 'filament-phone-number::components.phone-number-input';

// Nouveau code
protected string $view = 'filament-macymed-phone-number::components.phone-number-input';
```

## Dépendances

Ce plugin repose sur:
- [Filament PHP](https://filamentphp.com/)
- [brick/phonenumber](https://github.com/brick/phonenumber)

## Licence

Ce package est open-source sous la licence MIT.
