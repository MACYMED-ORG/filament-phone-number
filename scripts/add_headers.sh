#!/bin/bash
# scripts/add_headers.sh
# Usage: ./scripts/add_headers.sh [répertoire1] [répertoire2] ... [stamp=chemin/vers/stamp.txt]
# Par défaut, s'il n'y a pas de répertoire spécifié, on utilise "src" et "assets".
# Le fichier stamp par défaut est "assets/afl.txt".

DEFAULT_STAMP_FILE="assets/afl.txt"
TARGET_DIRS=()
STAMP_FILE=""

# Sépare les arguments en répertoires et éventuellement le paramètre stamp=
for arg in "$@"; do
    if [[ $arg == stamp=* ]]; then
        STAMP_FILE="${arg#stamp=}"
    else
        TARGET_DIRS+=("$arg")
    fi
done

# Si aucun répertoire n'est fourni, utiliser les valeurs par défaut
if [ ${#TARGET_DIRS[@]} -eq 0 ]; then
    TARGET_DIRS=("src" "assets")
fi

# Utiliser le fichier stamp fourni ou la valeur par défaut
if [ -z "$STAMP_FILE" ]; then
    STAMP_FILE="$DEFAULT_STAMP_FILE"
fi

if [ ! -f "$STAMP_FILE" ]; then
    echo "Fichier de stamp introuvable : $STAMP_FILE"
    exit 1
fi

STAMP_CONTENT=$(cat "$STAMP_FILE")
# Normalisation du contenu du stamp : suppression des retours à la ligne
STAMP_NORMALIZED=$(echo "$STAMP_CONTENT" | tr -d '\r\n')

for TARGET_DIR in "${TARGET_DIRS[@]}"; do
    if [ ! -d "$TARGET_DIR" ]; then
        echo "Répertoire non trouvé : $TARGET_DIR"
        continue
    fi
    echo "Traitement du répertoire : $TARGET_DIR"
    # Parcourt tous les fichiers PHP dans le répertoire
    find "$TARGET_DIR" -type f -iname "*.php" | while read -r file; do
        # Vérifier que le fichier commence par "<?php"
        first_line=$(head -n1 "$file")
        if [[ "$first_line" != "<?php" ]]; then
            echo "Fichier non éligible pour le stamp (ne commence pas par '<?php') : $file"
            continue
        fi
        # Récupérer l'intégralité du contenu du fichier
        file_content=$(cat "$file")
        # Normalisation du contenu du fichier (suppression des retours à la ligne)
        file_content_normalized=$(echo "$file_content" | tr -d '\r\n')
        # Vérifier si le stamp (normalisé) est déjà présent dans le fichier
        if [[ "$file_content_normalized" == *"$STAMP_NORMALIZED"* ]]; then
            echo "Stamp déjà présent dans : $file"
        else
            # Conserver le reste du contenu à partir de la 2ème ligne
            rest=$(tail -n +2 "$file")
            new_content="<?php
$STAMP_CONTENT
$rest"
            echo -e "$new_content" > "$file"
            echo "Stamp ajouté dans : $file"
        fi
    done
done
