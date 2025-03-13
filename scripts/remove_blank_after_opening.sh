#!/bin/bash
# Ce script supprime la deuxième ligne (si vide) de tous les fichiers PHP hors du dossier vendor

find ./ -type f -name "*.php" ! -path "./vendor/*" | while read -r file; do
    # Supprime la ligne 2 si elle est vide
    sed -i.bak '2{/^$/d;}' "$file"
    rm "$file.bak"
done
