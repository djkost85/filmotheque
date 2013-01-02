	function afficher(name){alert(name);
		fichier="classe_allocine/Filmotheque.class.php?fonction=afficher_lien_finder&nb_var=1&var0="+name;
		lancer_script(fichier,true);
	}
	
	function lancer_script(fichier,bool){
		if(window.XMLHttpRequest) // FIREFOX
		xhr_object = new XMLHttpRequest();
		else if(window.ActiveXObject) // IE
		xhr_object = new ActiveXObject("Msxml2.XMLHTTP");
		else
		return(false);
		xhr_object.open("GET", fichier, bool);
		xhr_object.send(null);
		if(xhr_object.readyState == 4) return(xhr_object.responseText);
		else return(false);
	}
	
	function rep_film(){
		var repertoire=document.getElementById('rep').value;
		document.location.href="dossier.php?dossier="+repertoire;
	}
	function acteur(){
		var acteur=document.getElementById('acteur').value;
		if(acteur==""){alert("l'acteur n'a pas été renseigné!!");return;}
		else{
			document.location.href="fiche_acteur_recherche.php?acteur="+acteur;
		}	
	}
	
	function acteur_film(){
		var acteur=document.getElementById('acteur').value;
		if(acteur==""){alert("l'acteur n'a pas été renseigné!!");return;}
		else{
			document.location.href="index.php?acteur="+acteur;
		}	
	}
	
	function film(){
		var film=document.getElementById('film').value;
		if(film==""){alert("le film n'a pas été renseigné!!");return;}
		else{
			document.location.href="index.php?titre="+film;
		}	
	}
	
	function change(liste){
		
		document.getElementById('content_left_'+temp).style.display="none";
		document.getElementById('entete_'+temp).style.display="none";
		id=liste.options[liste.options.selectedIndex].value;
		temp=id;
		titre=liste.options[liste.options.selectedIndex].text;
		document.getElementById('body').style.background="url('"+x[temp]+"') TOP CENTER";
		document.getElementById('content_left_'+id).style.display="inline";
		document.getElementById('entete_'+id).style.display="table-row";
		document.getElementById('principale3').style.marginTop='0px';
		hauteur = parseInt(window.innerHeight);	
		hauteur=hauteur - parseInt(document.getElementById('entete_'+id).offsetHeight);
		hauteur = hauteur - parseInt(document.getElementById('table_principale').offsetHeight)+13;
		if(hauteur>0){document.getElementById('principale3').style.marginTop=hauteur+'px';}
	}
	
	
	function change_genre(liste){
		var retour_liste ="";
		id=liste.options[liste.options.selectedIndex].value;
		fichier="classe_allocine/Filmotheque.class.php?fonction=liste_film_genre&nb_var=1&var0="+id;
		retour_liste=lancer_script(fichier,false);
		sel_rep=document.getElementById('select_principale');
		sel_rep.innerHTML=retour_liste;
		change(sel_rep);
	}

	
	function chargement(){
		sel_rep=document.getElementById('select_principale');
		change(sel_rep);
	}

	function lancer_film(lien){
		fichier="classe_allocine/Filmotheque.class.php?fonction=lancer_film&nb_var=1&var0="+lien;
		retour_liste=lancer_script(fichier,true);
	}
	
	function modifier_film_id(j,id){
		id_allocine=document.getElementById('new_id_allocine_'+j).value;
		fichier="classe_allocine/Filmotheque.class.php?fonction=modifier_film&nb_var=2&var0="+id_allocine+"&var1="+id;
		lancer_script(fichier,true);
	}
	
	function maj_fanart(j,id){
		lien=document.getElementById('new_fanart_'+j).value;
		fichier="classe_allocine/Filmotheque.class.php?fonction=maj_fanart_film&nb_var=2&var0="+id+"&var1="+lien;
		lancer_script(fichier,true);
	}
	
	
	
	function suppr(id){
		if(confirm('etes vous sur de supprimer ce film')){
			fichier="classe_allocine/Filmotheque.class.php?fonction=delete_film&nb_var=2&var0="+id+"&var1=true";
			lancer_script(fichier,true);
			
		}
	}
	
	
	function supprimer_lien(lien){
		if(confirm('etes vous sur de supprimer ce fichier')){
			fichier="classe_allocine/Filmotheque.class.php?fonction=delete_lien_film&nb_var=2&var0="+lien+"&var1=true";
			lancer_script(fichier,true);
			
		}
	}
	
	function voter_interet(id,value){
		fichier="classe_allocine/Filmotheque.class.php?fonction=modifer_interet_film&nb_var=2&var0="+id+"&var1="+value;
		lancer_script(fichier,true);
		for(i=1;i<=value;i++){
			document.getElementById('etoile_'+id+'_'+i).src='images/etoile_pleine.png';
		}
		j=parseInt(value)+1;
		for(i=j;i<=5;i++){
			document.getElementById('etoile_'+id+'_'+i).src='images/etoile_vide.png';
		}

	}
	
	function voter_qualite(id,liste){
		value=liste.options[liste.options.selectedIndex].value;
		fichier="classe_allocine/Filmotheque.class.php?fonction=modifer_qualite_lien_film&nb_var=2&var0="+id+"&var1="+value;
		lancer_script(fichier,true);
	}
	
	
	
		
	function ouvrir(){
		document.getElementById('login').style.overflow='visible';
		document.getElementById('container').style.display='none';
		hauteur=parseInt(document.getElementById('principale3').style.marginTop)+38;
		document.getElementById('principale3').style.marginTop=hauteur+'px';
		
	}
	
	function fermer(){
		document.getElementById('login').style.overflow='hidden';
		document.getElementById('container').style.display='inline';
		hauteur=parseInt(document.getElementById('principale3').style.marginTop)-38;
		document.getElementById('principale3').style.marginTop=hauteur+'px';
	}
	
	
	
	
	
	
	
	/*serie*/
	
	function rep_serie(){
		var repertoire=document.getElementById('rep').value;
		document.location.href="dossier_serie.php?dossier="+repertoire;	
	}
	
	
	function maj_serie(id){
		fichier="classe_allocine/Filmotheque.class.php?fonction=maj_serie&nb_var=1&var0="+id;
		lancer_script(fichier,true);
	}
	
	function maj_all_serie(id){
		fichier="classe_allocine/Filmotheque.class.php?fonction=maj_all_serie";
		lancer_script(fichier,true);
	}




