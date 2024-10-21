<?php
include_once RACINE . '/classes/Etudiant.php';
include_once RACINE . '/connexion/Connexion.php';
include_once RACINE . '/dao/IDao.php';
class EtudiantService implements IDao {
 private $connexion;
 function __construct() {
 $this->connexion = new Connexion();
 }
 public function create($o) {
   $image = $o->getImage();
   $nom_image = time() . '.jpg';  // Générer un nom unique pour l'image
   
   // Correction du chemin d'upload
   $chemin_upload = __DIR__ . '/../uploads/' . $nom_image;
   
   // Assurez-vous que le dossier uploads existe
   if (!file_exists(__DIR__ . '/../uploads/')) {
       mkdir(__DIR__ . '/../uploads/', 0777, true);
   }

   // Décoder l'image base64
   $donnees_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

   // Sauvegarder le fichier image
   if (file_put_contents($chemin_upload, $donnees_image)) {
       // L'image a été sauvegardée avec succès
       $nom_image_bdd = 'uploads/' . $nom_image; // Chemin relatif pour la BDD
   } else {
       // Erreur lors de la sauvegarde de l'image
       $nom_image_bdd = '';
   }

   $query = "INSERT INTO Etudiant (`id`, `nom`, `prenom`, `ville`, `sexe`, `image`) "
          . "VALUES (NULL, '" . $o->getNom() . "', '" . $o->getPrenom() . "', '"
          . $o->getVille() . "', '" . $o->getSexe() . "', '" . $nom_image_bdd . "');";

   $req = $this->connexion->getConnexion()->prepare($query);
   $req->execute() or die('Erreur SQL');
}
 public function delete($o) {
 $query = "delete from Etudiant where id = " . $o->getId();
 $req = $this->connexion->getConnexion()->prepare($query);
 $req->execute() or die('Erreur SQL');
 }
 public function findAll() {
 $etds = array();
 $query = "select * from Etudiant";
 $req = $this->connexion->getConnexion()->prepare($query);
 $req->execute();
 while ($e = $req->fetch(PDO::FETCH_OBJ)) {
 $etds[] = new Etudiant($e->id, $e->nom, $e->prenom, $e->ville, $e->sexe);
 }
 return $etds;
 }
 public function findById($id) {
 $query = "select * from Etudiant where id = " . $id;
 $req = $this->connexion->getConnexion()->prepare($query);
 $req->execute();
 if ($e = $req->fetch(PDO::FETCH_OBJ)) {
 $etd = new Etudiant($e->id, $e->nom, $e->prenom, $e->ville, $e->sexe);
 }
 return $etd;
 }
 public function update($o) {
    $updateImageQuery = "";
    
    // Gestion de l'image si elle est fournie
    if ($o->getImage() && !empty($o->getImage())) {
        $nom_image = time() . '.jpg';  // Générer un nom unique pour l'image
        $chemin_upload = __DIR__ . '/../uploads/' . $nom_image;
        
        // Assurez-vous que le dossier uploads existe
        if (!file_exists(__DIR__ . '/../uploads/')) {
            mkdir(__DIR__ . '/../uploads/', 0777, true);
        }

        // Décoder l'image base64
        $donnees_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $o->getImage()));

        // Sauvegarder le fichier image
        if (file_put_contents($chemin_upload, $donnees_image)) {
            // L'image a été sauvegardée avec succès
            $nom_image_bdd = 'uploads/' . $nom_image;
            $updateImageQuery = ", `image` = '" . $nom_image_bdd . "'";
            
            // Suppression de l'ancienne image si elle existe
            $stmt = $this->connexion->getConnexion()->prepare("SELECT image FROM etudiant WHERE id = ?");
            $stmt->execute([$o->getId()]);
            $oldImage = $stmt->fetchColumn();
            
            if ($oldImage && file_exists(__DIR__ . '/../' . $oldImage)) {
                unlink(__DIR__ . '/../' . $oldImage);
            }
        }
    }

    $query = "UPDATE `etudiant` SET 
              `nom` = '" . $o->getNom() . "', 
              `prenom` = '" . $o->getPrenom() . "', 
              `ville` = '" . $o->getVille() . "', 
              `sexe` = '" . $o->getSexe() . "'" . 
              $updateImageQuery . 
              " WHERE `etudiant`.`id` = " . $o->getId();

    $req = $this->connexion->getConnexion()->prepare($query);
    $req->execute() or die('Erreur SQL');
}
 public function findAllApi() {
    $query = "select * from Etudiant";
    $req = $this->connexion->getConnexion()->prepare($query);
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
   }
   
}
