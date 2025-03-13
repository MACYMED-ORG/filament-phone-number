#!/bin/bash
# scripts/autoindex.sh - Version modifiée pour créer index.php même si aucun fichier PHP n'est présent
# Usage: ./scripts/autoindex.sh [répertoire1] [répertoire2] ...
# Par défaut, si aucun répertoire n'est spécifié, on utilise "src" et "assets".

if [ "$#" -eq 0 ]; then
    TARGET_DIRS=("src" "assets")
else
    TARGET_DIRS=("$@")
fi

for TARGET_DIR in "${TARGET_DIRS[@]}"; do
    if [ ! -d "$TARGET_DIR" ]; then
        echo "Répertoire non trouvé : $TARGET_DIR"
        continue
    fi
    echo "Traitement du répertoire : $TARGET_DIR"
    # Création de l'index dans le répertoire cible s'il n'existe pas déjà
    if [ ! -f "$TARGET_DIR/index.php" ]; then
        cat > "$TARGET_DIR/index.php" <<'EOF'
<?php
// Fichier index auto-généré pour empêcher l'affichage du contenu du répertoire
header('Location: /');
exit;
EOF
        echo "Index créé dans : $TARGET_DIR"
    fi

    # Parcours récursif des sous-répertoires
    find "$TARGET_DIR" -type d | while read -r dir; do
        if [ "$dir" = "$TARGET_DIR" ]; then
            continue
        fi
        # Recherche des fichiers PHP dans le dossier (en excluant index.php)
        php_files=$(find "$dir" -maxdepth 1 -type f -iname "*.php" ! -iname "index.php")
        if [ -n "$php_files" ]; then
            if [ ! -f "$dir/index.php" ]; then
                cat > "$dir/index.php" <<'EOF'
<?php
// Fichier index auto-généré pour empêcher l'affichage du contenu du répertoire
header('Location: /');
exit;
EOF
                echo "Index créé dans : $dir"
            fi
        fi
    done
done
