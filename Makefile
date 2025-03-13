.PHONY: init composer-require fix-blank-lines remove-vendor composer-install autoindex cs-fixer rector phpstan phpmd header-stamp all

# Vérifie que les packages requis sont présents dans composer.json et les installe si besoin
composer-require:
	@echo "Vérification des packages requis dans composer.json..."
	@if ! grep -q '"rector/rector"' composer.json; then \
	    echo "Installation de rector/rector..."; \
	    composer require rector/rector --dev; \
	fi
	@if ! grep -q '"phpstan/phpstan"' composer.json; then \
	    echo "Installation de phpstan/phpstan..."; \
	    composer require phpstan/phpstan --dev; \
	fi
	@if ! grep -q '"phpmd/phpmd"' composer.json; then \
	    echo "Installation de phpmd/phpmd..."; \
	    composer require phpmd/phpmd --dev; \
	fi

# Cible d'initialisation qui s'assure que les packages requis sont installés
init: composer-require
	@if [ ! -d vendor ]; then \
	    echo "Installation des dépendances..."; \
	    composer install; \
	fi

# Supprime le répertoire vendor
remove-vendor:
	@echo "Suppression du répertoire vendor..."
	rm -rf vendor

# Réinstalle les dépendances via Composer
composer-install:
	@echo "Installation des dépendances..."
	composer install

# Exécute Autoindex en utilisant le script Bash dans le répertoire scripts
autoindex: init
	@echo "Exécution de autoindex..."
	bash ./scripts/autoindex.sh src assets

# Exécute PHP CS Fixer pour corriger le code
cs-fixer: init
	@echo "Exécution de PHP CS Fixer..."
	php-cs-fixer fix

# Supprime les lignes vides après le tag d'ouverture dans les fichiers PHP via un script Bash
fix-blank-lines:
	@echo "Suppression des lignes vides après le tag d'ouverture dans les fichiers PHP..."
	bash ./scripts/remove_blank_after_opening.sh
	@echo "Fichiers corrigés."

# Exécute Rector pour la refactorisation automatique du code
rector: init
	@echo "Exécution de Rector pour la refactorisation automatique..."
	vendor/bin/rector process .

# Exécute PHPStan avec la configuration par défaut
phpstan: init
	@echo "Exécution de PHPStan..."
	vendor/bin/phpstan analyse --configuration=tests/phpstan/phpstan.neon .

# Exécute PHPMD pour analyser le code
phpmd: init
	@echo "Exécution de PHPMD..."
	vendor/bin/phpmd . text cleancode,codesize,controversial,design,naming,unusedcode

# Applique le header stamp en utilisant le script PHP dans le répertoire scripts
# On précise ici les répertoires (src et assets) et le fichier stamp via l'argument stamp=
header-stamp: init
	@echo "Exécution du header stamp..."
	php ./scripts/add_headers.php src assets stamp=assets/afl.txt

# Tâche globale qui exécute toutes les étapes dans l'ordre
all: remove-vendor composer-install autoindex fix-blank-lines cs-fixer rector phpstan header-stamp
	@echo "Toutes les tâches ont été exécutées."
