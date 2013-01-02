<!DOCTYPE html>
<html>
	<head>
		<title>Error table :: API Allocin� Helper</title>
        <style type="text/css">
            body { background-color: #FFFFFF; width: 90%; margin: auto; font-family: Lucida Grande, Verdana; font-size: 0.9em; color: #4F5155 }
            h1 { font-size: 1.6em; color: #2A63A1; text-align: center }
            h5 { text-align: center; font-size: 0.8em; color: #919191 }
            table { width: 100%; border-collapse: collapse; }
            td, th { padding: 0.5em; vertical-align: top }
            td { background-color: #f9f9f9; border: 1px solid #D0D0D0; font-size: 0.8em }
            table#erreurs td { max-width: 6em }
            th { background-color: #3DA9D4; border: 1px solid #3E6F82; color: white }
            table#erreurs tr td:first-child { width: 2em; text-align: center; background-color: #E8C227; border: 1px solid #8F7D57; color: white; font-weight: bold; font-size: 1em }
            table#erreurs tr th:first-child { background-color: transparent; border: 1px solid transparent; font-size: 1em }
            a { color: #003399; text-decoration: none }
            a:hover { text-decoration: underline }
            ::selection { background-color: #49A7C4; color: white }
            
            td.ex { overflow: scroll; background-color: #FFFFDE; max-width: 30em }
            td.ex > code { overflow: scroll; max-width: 30em }
            .var { color: blue }
            
            p.fct { text-align: center; font-weight: bold }
        </style>
    </head>
	<body>
        <div id='content'>
            
            <h1>Classe AlloHelper</h1>
            <table>
                <tr>
                    <td>
                        <p class='fct'>AlloHelper::set ( array <span class='var'>$options</span> )</p>
                        array <span class='var'>$options</span>: Le tableau des options
                    </td>
                    <td>
                        Ajouter/modifier des pr�r�glages destin�s � �tre ajout�s � l'URL.<br />
                        Chaque paire "cl�" => valeur ou "cl�=valeur" dans $options sera enregistr�e dans les pr�r�glages.
                    </td>
                    <td class='ex'><?php highlight_string('<?php
    // Cr�ation de l\'objet AlloHelper
    $allo = new AlloHelper();
    
    // Tableau des options
    $options = array(
        \'page\' => 3,
        \'mediaFmt\' => \'mp4-lc\',
        \'filter\' => array(\'movie\', \'person\', \'tvseries\')
    );
    
    // On envoie les options
    $allo->set( $options );
?>') ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class='fct'>AlloHelper::set ( string <span class='var'>$key</span>, mixed <span class='var'>$value</span> )</p>
                        <p>string <span class='var'>$key</span>: Le nom du pr�r�glage � ajouter.</p>
                        <p>mixed <span class='var'>$value</span>: La valeur du pr�r�glage � ajouter. Peut-�tre une cha�ne de caract�re ou un nombre. Les tableaux seront implos� avec une virgule.</p>
                    </td>
                    <td>
                        Ajouter/modifier un pr�r�glage destin� � �tre ajout� � l'URL.
                    </td>
                    <td class='ex'><?php highlight_string('<?php
    // Cr�ation de l\'objet AlloHelper
    $allo = new AlloHelper();
    
    // On envoie les options
    $allo->set( \'page\', 3 );
    $allo->set( \'mediaFmt\', \'mp4-lc\' );
    $allo->set( \'filter\', array (\'movie\', \'person\', \'tvseries\') );
?>') ?>
                    </td>
                </tr>
            </table>
            
            <h1>Table of errors / Tableau des erreurs / Tabelle der Fehler / Tabla de errores</h1>
            <table id='erreurs'>
                <thead>
                    <tr>
                        <th></th>
                        <th>English <img alt="eng" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALBAMAAACEzBAKAAAAMFBMVEWEHTjL1egKJpDlQkH+/v5cdMb6LzFWYrAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWNkdhAAAACXBIWXMAAAsSAAALEgHS3X78AAAAXElEQVR42mNgFFISYFRSEGRQdgl1Swk1cWJQNXF1Swl2CWJQDXZxSzFxDWIQcQEyXFwcGdjS0tKS09ISECKqIc5AhilQsYupW0qJcxCDijPQnHIXIwZBJpDJigIAGdAVgOXMNEUAAAAASUVORK5CYII=" /></th>
                        <th>Fran�ais <img alt="fr" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALBAMAAACEzBAKAAAAMFBMVEVylNLZ2dnnAwC0xeb09PT4lYz0iH7zfnLxcmYXR6XIAACfn58AAAAAAAAAAAAAAADsO+33AAAACXBIWXMAAAsSAAALEgHS3X78AAAAO0lEQVR42mNgYGAUFBRSUmJgNjZxcXENS4Iw3DOgDLe0IgjDIw0uksQwGcLIwsXwADJmzpy9e/euVasA9QMaMbO4rHkAAAAASUVORK5CYII=" /></th>
                        <th>Deutsch  <img alt="de" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALBAMAAACEzBAKAAAAMFBMVEU+Pi5TU1OnPi7vMjK1oC755mcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD8n9hIAAAACXBIWXMAAAsSAAALEgHS3X78AAAAIUlEQVR42mNggAFGQTAQwMJQNgYDIywM11AwCMHCcIECAASHD+CkPe2GAAAAAElFTkSuQmCC" /></th>
                        <th>Espa�ol  <img alt="es" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALBAMAAACEzBAKAAAAMFBMVEXVAAD8dnXX1wD8/FHimEzj0L0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA3C9leAAAACXBIWXMAAAsSAAALEgHS3X78AAAAKElEQVR42mNggAFGQTAQQDCUjcHACMgQgTJMRYOhDEcYwwXCwNQOAwDcSQnI9E35rQAAAABJRU5ErkJggg==" /></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='code'>0</td>
                        <td>Unknown error.</td>
                        <td>Erreur inconnue.</td>
                        <td>Unbekannter Fehler.</td>
                        <td>Error desconocido.</td>
                    </tr>
                    <tr>
                        <td class='code'>1</td>
                        <td>The extension <a href="http://www.php.net/manual/fr/book.curl.php">php_curl</a> must be installed with PHP or function <a href="http://www.php.net/manual/fr/function.file-get-contents.php">file_get_contents</a> must be enabled.</td>
                        <td>L'extension <a href="http://www.php.net/manual/fr/book.curl.php">php_curl</a> doit �tre install�e avec PHP, ou la fonction <a href="http://www.php.net/manual/fr/function.file-get-contents.php">file_get_contents</a> doit �tre activ�e.</td>
                        <td>Die Erweiterung <a href="http://www.php.net/manual/fr/book.curl.php">php_curl</a> muss mit PHP installiert werden oder die Funktion <a href="http://www.php.net/manual/fr/function.file-get-contents.php">file_get_contents</a> muss aktiviert sein.</td>
                        <td>El <a href="http://www.php.net/manual/fr/book.curl.php">php_curl</a> extensi�n debe ser instalado con PHP o <a href="http://www.php.net/manual/fr/function.file-get-contents.php">file_get_contents</a> funci�n debe estar activada.</td>
                    </tr>
                    <tr>
                        <td class='code'>2</td>
                        <td>An error occurred while retrieving the data.</td>
                        <td>Une erreur s'est produite lors de la r�cup�ration des donn�es.</td>
                        <td>Fehler beim Abrufen der Daten.</td>
                        <td>Se produjo un error al recuperar los datos.</td>
                    </tr>
                    <tr>
                        <td class='code'>3</td>
                        <td>An error occurred when converting data.</td>
                        <td>Une erreur s'est produite lors de la conversion des donn�es.</td>
                        <td>Fehler beim Konvertieren von Daten.</td>
                        <td>Se produjo un error al convertir los datos.</td>
                    </tr>
                    <tr>
                        <td class='code'>4</td>
                        <td>The string of keywords should contain more than one character.</td>
                        <td>La cha�ne de mots-cl�s doit contenir plus d'un caract�re.</td>
                        <td>Die Schl�sselw�rter String enthalten sollte mehr als ein Zeichen.</td>
                        <td>La cadena de palabras clave debe contener m�s de un car�cter.</td>
                    </tr>
                    <tr>
                        <td class='code'>5</td>
                        <td>Screenrush returned an error.</td>
                        <td>Allocin� a renvoy� une erreur.</td>
                        <td>Filmstarts einen Fehler zur�ckgegeben.</td>
                        <td>Sensacin� devolvi� un error.</td>
                    </tr>
                    <tr>
                        <td class='code'>6</td>
                        <td>This offset does not exist.</td>
                        <td>Cet offset n'existe pas.</td>
                        <td>Dieser Offset ist nicht vorhanden.</td>
                        <td>Este offset no existe.</td>
                    </tr>
                    
                    <!--
                    
                    <tr>
                        <td class='code'>7</td>
                        <td>No result for the movie requested.</td>
                        <td>Pas de r�sultat pour le film demand�.</td>
                        <td>Kein Ergebnis f�r den Film anzubieten.</td>
                        <td>No hay ning�n resultado para la pel�cula requerido.</td>
                    </tr>
                    <tr>
                        <td class='code'>9</td>
                        <td>Connection error.</td>
                        <td>Erreur de connexion.</td>
                        <td>Verbindungsfehler.</td>
                        <td>Error de conexi�n.</td>
                    </tr>
                    <tr>
                        <td class='code'>11</td>
                        <td>This is not a link to an image.</td>
                        <td>Ceci n'est pas un lien vers une image.</td>
                        <td>Dies ist nicht ein Link zu einem Bild.</td>
                        <td>Esto no es un enlace a una imagen.</td>
                    </tr>
                    <!--<tr>
                        <td class='code'>code</td>
                        <td>eng</td>
                        <td>fr</td>
                        <td>de</td>
                        <td>es</td>
                    </tr>-->
                </tbody>
            </table>
            
            <h5>Report errors / Signaler une erreur / Fehler melden / Informe de errores: <a href="mailto:etn2010@gmail.com">Etn2010@gmail.com</a><br /><a href="https://sites.google.com/site/apiallocine/">API Allocin� Helper</a> - version 1.4</h5>
        </div>
	</body>
</html>