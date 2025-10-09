<?php
// Script pour capturer et afficher les logs en temps réel
$logFile = 'debug_cuve.log';

// Effacer le fichier de log précédent
file_put_contents($logFile, '');

echo "=== SURVEILLANCE DES LOGS EN TEMPS RÉEL ===\n";
echo "Allez sur votre navigateur et soumettez le formulaire maintenant.\n";
echo "Les logs apparaîtront ci-dessous...\n\n";

$lastSize = 0;
while (true) {
    if (file_exists($logFile)) {
        clearstatcache();
        $currentSize = filesize($logFile);
        
        if ($currentSize > $lastSize) {
            $content = file_get_contents($logFile);
            $newContent = substr($content, $lastSize);
            echo $newContent;
            $lastSize = $currentSize;
        }
    }
    
    usleep(100000); // 0.1 seconde
}
?>
