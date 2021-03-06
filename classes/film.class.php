<?php
require_once('factory.class.php');
require_once('commun.class.php');
require_once('database.class.php');
require_once('api_allocine_helper_2.2.class.php');

/**
*Structure de la table film:
*id_film
*/

class film{

	private $pdo;
	private $genre;
	private $acteur;
	private $lien;
	private $mot_corrige;
	private $allo;




	function __construct(){
		$this->pdo=database::getInstance();
		$this->genre=factory::load("genre");
		$this->acteur=factory::load("acteurs");
		$this->lien=factory::load("liens");
		//$this->mot_corrige=new mot_corrige();
		$this->mot_corrige=factory::load("mot_corrige");
		$this->allo=new AlloHelper();
	}


	public function list_film($aParams,$limit=false){
		$select="SELECT distinct f.* ";
		$from=" FROM film f ";
		$sCondition="";
		$aCondition=array();
		if(!empty($aParams)){
			foreach($aParams as $key=>$value){
				switch ($key) {
					case 'id_film':
						$sCondition.=($sCondition=="")?"WHERE ":"AND ";
						$sCondition.="f.id_film=:id_film";
						$aCondition["id_film"]=$value;
						break;
					case 'titre':
						$sCondition.=($sCondition=="")?"WHERE ":"AND ";
						$sCondition.=" f.titre LIKE :titre1 or f.titre LIKE :titre2 or f.titre_original LIKE :titre1 or f.titre_original LIKE :titre2";
						$aCondition["titre1"]="%".$value."%";
						$aCondition["titre2"]="%".utf8_decode($value)."%";
						break;
					case 'doublon':
						$sCondition.=($sCondition=="")?"WHERE ":"AND ";
						$sCondition.="f.id_film in(SELECT id_film FROM liens group by id_film  having count(id_film)>=2)";
						break;
					case 'acteur':
						$from.=",acteur_film af, acteur a ";
						$sCondition.=($sCondition=="")?"WHERE ":"AND ";
						$sCondition.="a.id_acteur=af.id_acteur AND af.id_film=f.id_film AND a.nom LIKE :acteur";
						$aCondition["acteur"]="%".$value."%";
						break;
					
					default:
						break;
				}
			}
		}
		$stmt= $this->pdo->prepare($select.$from.$sCondition." order by f.date_crea,f.titre");
		$stmt->execute($aCondition);
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		if($limit!==false){
			$result=$result[0];
		}
		return $result;
	}


	public function creer_film($id_film, $dossier,$dossier_old,$file, $file_old){
		$this->creer_data_film($id_film);
		$this->lien->ajouter_lien_film($id_film,$dossier,$dossier_old,$file, $file_old);
		return $id_film;			 
	}

	private function creer_data_film($id_film){
		if(!$this->verif_existe_film($id_film)){
			$info=$this->allo->movie( $id_film);
			$infos=$info->getArray();	
			$titre_original=(isset($infos['originalTitle']))?$infos['originalTitle']:"";
			$titre_original=(isset($infos['title']))?$infos['title']:"";
			$titre_original=(isset($infos['productionYear']))?$infos['productionYear']:"";
			$titre_original=(isset($infos['runtime']))?$infos['runtime']:"";
			$titre_original=(isset($infos['synopsis']))?$infos['synopsis']:"";
			$titre_original=(isset($infos['trailer']['href']))?$infos['trailer']['href']:"";
			$titre_original=(isset($infos['poster']["href"]))?$infos['poster']["href"]:"";
			$fanart=images::fanart($titre,$annee);			
			$this->insert_film($id_film,$titre_original,$titre,$annee,$duree,$synopsis,$bande,$poster,$fanart);
				//remplissage du genre
			if(isset($infos['genre'])){
				$this->genre->ajout_genres_film($infos['genre'],$id_film);
			}	
			if(isset($infos['castMember'])){	
				$this->acteur->ajoutActeursFilm($id_film,$infos['castMember']);
			}
		}	 
	}




	public function modifier_film($id_film,$id_old){
		$this->delete_film($id_old);
		$this->creer_data_film($id_film);
		$this->lien->update_liens_id_film($id_film,$id_old);
		return $id_film;			
	}



	public function delete_film($id_film,$supr_lien=false){
		if($supr_lien=="true" || $supr_lien == true){$this->delete_all_liens_film($id_film);}
		$stmt= $this->pdo->prepare("DELETE FROM film where id_film =:id_film LIMIT 1");
		$stmt->execute(array('id_film' => $id_film));		
	}


	/**a faire
	afficher la bande annonce
	ne pas appeller ici update mot_corrige
	*/
	public function rechercher_film_allocine($name,$name_init,$echo=TRUE){
		$this->mot_corrige->update_mot_corrige($name_init,$name);
		$film=str_replace(".avi","",htmlspecialchars($name,ENT_QUOTES));
		$film=str_replace(".mkv","",$film);
		$film=str_replace(".mp4","",$film);
		$film=str_replace(".AVI","",$film);
		$film=str_replace(".MKV","",$film);
		$film=str_replace(".MP4","",$film);
		$allo= new AlloHelper;
		$info=$allo->search( $film, 1, 15, false, array("movie") );
		$infos=$info->getArray();

		if($infos["totalResults"]==1){	 		  
			if(isset($infos["movie"][0]["code"])){
				$id_film=$infos["movie"][0]["code"];
				$info=$allo->movie( $id_film, 'small');
				$infos=$info->getArray();
				$poster="";
				if(isset($infos["poster"]["href"])){$poster=htmlspecialchars($infos["poster"]["href"],ENT_QUOTES);}
				if(!$this->verif_existe_film($id_film)){
					$synopsis=(isset($infos["synopsisShort"]))?$infos["synopsisShort"]:"";
				}
				else{$synopsis='<p onclick="lancer_video(\''.$this->lien->lien_film_unique($id_film).'\')">ce film existe deja</p>';}
				$tab=array($id_film,$synopsis,$poster);		 		 	
			}
			else{$tab=array("","film introuvable","");}
			if($echo){echo utf8_encode("1__synopsis__".$tab[0]."__synopsis__".$tab[1]."__synopsis__".$tab[2]); }
		}
		else if($infos["totalResults"]>1){$tab=array();
			if($echo){echo '<table>';}
			foreach($infos["movie"] as $array){
				$id_film=$array["code"];
				$titre=$array["originalTitle"];	 				
				$annee=$array["productionYear"];
				$poster=$array["posterURL"];					
				if($echo){			
					if($this->verif_existe_film($id_film)){
						echo '<tr><td><img  onclick="lancer_video(\''.$this->lien->lien_film_unique($id_film).'\')" alt="poster" src="'.$poster.'" width="50px" /><br>'.$titre.' ('.$annee.')<br>'.$id_film;
						echo '<br>ce film existe deja';
					}
					else{
						echo '<tr><td><img src="'.$poster.'" width="50px" /><br>'.$titre.' ('.$annee.')<br>'.$id_film;
					}
					echo '</td></tr>';						
				}
				$tab[]=array($id_film,$titre,$annee,$poster);	 
			}
			if($echo){echo '</table>';}
		}

		
		
		
		else{
			$tab=array("0","","film introuvable","");
			if($echo){echo "film introuvable"; }
		}
		
		return $tab;
	}



	/**a faire
	afficher la bande annonce*/
	public function rechercher_film_id_film($id_film,$echo=TRUE){
		$info=$this->allo->movie( $id_film, 'small');
		$infos=$info->getArray();
		$poster="";
		if(isset($infos["poster"]["href"])){$poster=htmlspecialchars($infos["poster"]["href"],ENT_QUOTES);}
		if(!$this->verif_existe_film($id_film)){
			$synopsis=(isset($infos["synopsisShort"]))?$infos["synopsisShort"]:"";
		}
		else{$synopsis="ce film existe deja";}
		$tab=array($id_film,$synopsis,$poster);	
		if(!isset($infos["code"])){$tab=array("","film introuvable","");}
		if($echo){echo utf8_encode($tab[0]."__synopsis__".$tab[1]."__synopsis__".$tab[2]); }
		return $tab;
	}




	private function verif_existe_film($id_film){
		$stmt= $this->pdo->prepare("SELECT count(id_film) as nb FROM film WHERE id_film = :id_film ");
		$stmt->execute(array('id_film' => $id_film)) ;
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$nb= $row['nb']+1;
		if($nb > 1){				
			return true;	
		}
		else return false;
	}



	private function insert_film($id_film,$titre_original,$titre,$annee,$duree,$synopsis,$bande,$poster,$fanart){
		$stmt= $this->pdo->prepare("INSERT INTO film (id_film,titre_original,titre,annee_production,duree,synopsis,bande_annonce,poster,fanart)
									VALUES(:id_film,:titre_original,:titre,:annee,:duree,:synopsis,:bande,:poster,:fanart)");
		$stmt->execute(array('id_film' => $id_film,
			'titre_original' => $titre_original,
			'titre' => $titre,
			'annee' => $annee,
			'duree' => $duree,
			'synopsis' => $synopsis,
			'bande' => $bande,
			'poster' => $poster,
			'fanart' => $fanart)) ;
		return $id_film;	
	}



	public function modifier_interet_film($id_film,$value){
		$stmt= $this->pdo->prepare("update film SET interet=:value where id_film=:id_film");
		$stmt->execute(array('id_film' => $id_film,'value' => $value));
	}



	public function modifier_film_manuellement(){}


	public function lister_dossier_film($dirname){
		$tab=fichier::get_video_repertoire($dirname);
		return array("tab"=>$tab,"nb"=>sizeof($tab)); 
	 }





}


	?>