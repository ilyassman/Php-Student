<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../racine.php';
    include_once RACINE . '/service/EtudiantService.php';

    // Appeler la fonction pour mettre à jour l'étudiant
    updateEtudiant();
}

function updateEtudiant() {
    // Récupérer les données envoyées par la requête POST
    extract($_POST);

    // Créer une instance du service étudiant
    $es = new EtudiantService();

    // Mettre à jour l'étudiant en passant les nouvelles informations
    $es->update(new Etudiant($id, $nom, $prenom, $ville, $sexe,$image));

    // Répondre avec les données mises à jour
    header('Content-type: application/json');
    echo json_encode($es->findAllApi());
}
