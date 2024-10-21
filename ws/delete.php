<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../racine.php';
    include_once RACINE . '/service/EtudiantService.php';
    delete();
}

function delete() {
    // Récupérer les données envoyées par la requête POST
    extract($_POST);
    
    // Instanciation du service
    $es = new EtudiantService();
    
    // Supprimer l'étudiant par l'ID
    $es->delete(new Etudiant($id, null, null, null, null, null));
    
    // Répondre avec les données mises à jour après suppression
    header('Content-type: application/json');
    echo json_encode($es->findAllApi());
}
?>
