<?php
function chargerClasse($classe) {
    $dossierClass = __DIR__ . '/../class/';
    $possibilites = [];
    $possibilites[] = $classe . '.class.php';
    $possibilites[] = strtolower($classe) . '.class.php';

    if (strpos($classe, 'Vue') === 0) {
        $suffixe = substr($classe, 3); 
        $possibilites[] = 'Vue.' . $suffixe . '.class.php';
        $possibilites[] = 'Vue.' . strtolower($suffixe) . '.class.php';
    }

    foreach ($possibilites as $fichier) {
        $chemin = $dossierClass . $fichier;
        if (file_exists($chemin)) {
            require_once $chemin;
            return;
        }
    }
}

spl_autoload_register('chargerClasse');
?>