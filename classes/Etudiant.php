<?php
class Etudiant {
 private $id;
 private $nom;
 private $prenom;

 private $ville;
 private $sexe;
 private $image;
 
 function __construct($id, $nom, $prenom, $ville, $sexe,$image) {
 $this->id = $id;
 $this->nom = $nom;
 $this->prenom = $prenom;
 $this->ville = $ville;
 $this->sexe = $sexe;
 $this->image=$image;
 }
 function getId() {
 return $this->id;
 }
 function getImage() {
    return $this->image;
    }
 function getNom() {
 return $this->nom;
 }
 function getPrenom() {return $this->prenom;
 }
 function getVille() {
 return $this->ville;
 }
 function getSexe() {
 return $this->sexe;
 }
 function setId($id) {
 $this->id = $id;
 }
 function setNom($nom) {
 $this->nom = $nom;
 }
 function setPrenom($prenom) {
 $this->prenom = $prenom;
 }
 function setVille($ville) {
 $this->ville = $ville;
 }
 function setSexe($sexe) {
 $this->sexe = $sexe;
 }
 public function __toString() {
 return $this->nom . " " . $this->prenom;
 }
}
