<?php
/**
 * Script pour mettre à jour les fichiers PHP en insérant un header stamp.
 *
 * Usage (en ligne de commande) :
 *   php scripts/add_headers.php [directory1] [directory2] ... [stamp=path/to/stamp.txt]
 *
 * Par défaut, si aucun répertoire n'est spécifié, "src" et "assets" sont utilisés.
 * Le fichier stamp par défaut est "assets/afl.txt".
 */

// Récupération des arguments
$dirs = [];
$stampFile = 'assets/afl.txt'; // valeur par défaut

if ($argc < 2) {
    $dirs = ['src', 'assets'];
} else {
    for ($i = 1; $i < $argc; ++$i) {
        if (strpos($argv[$i], 'stamp=') === 0) {
            $stampFile = substr($argv[$i], strlen('stamp='));
        } else {
            $dirs[] = $argv[$i];
        }
    }
    if (empty($dirs)) {
        $dirs = ['src', 'assets'];
    }
}

if (!file_exists($stampFile)) {
    echo "Fichier de stamp introuvable : $stampFile" . PHP_EOL;
    exit(1);
}

$stamp = file_get_contents($stampFile);
// On utilise rtrim() pour enlever les espaces et retours en fin de chaîne
$stampTrim = rtrim($stamp);

/**
 * Traite un fichier PHP :
 * - Supprime toutes les occurrences existantes du stamp (en ignorant les variations d'espacement).
 * - Si le fichier commence par "<?php", supprime les espaces et retours immédiatement après,
 *   puis insère le stamp sur une nouvelle ligne après "<?php".
 *
 * @param string $file chemin complet du fichier
 * @param string $stamp le contenu original du stamp
 * @param string $stampTrim le stamp "trimé"
 */
function processFile(string $file, string $stamp, string $stampTrim): void
{
    $content = file_get_contents($file);

    // Construction d'une regex flexible pour supprimer le stamp existant
    $pattern = '/' . preg_replace('/\s+/', '\\s+', preg_quote($stampTrim, '/')) . '/s';
    $content = preg_replace($pattern, '', $content);

    // Vérifie que le fichier commence par "<?php"
    if (strpos($content, '<?php') !== 0) {
        return;
    }

    // Remplace la portion immédiatement après "<?php" (espaces et retours) par un saut de ligne,
    // suivi du contenu stampTrim et d'un saut de ligne.
    $content = preg_replace('/^(<\?php)[\s\r\n]*/s', '$1' . "\n" . $stampTrim . "\n", $content, 1);

    file_put_contents($file, $content);
    echo "Fichier mis à jour : $file" . PHP_EOL;
}

// Parcours des répertoires et traitement des fichiers PHP
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        echo "Répertoire non trouvé : $dir" . PHP_EOL;
        continue;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile() && strtolower($fileInfo->getExtension()) === 'php') {
            processFile($fileInfo->getPathname(), $stamp, $stampTrim);
        }
    }
}
