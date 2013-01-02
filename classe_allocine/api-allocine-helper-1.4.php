<?php
    
    /**
    * API Allocin� Helper
    * 
    * Les classes AlloHelper, AlloData et AlloImage permettent d'utiliser l'API d'Allocin� plus facilement.
    * 
    * @licence http://creativecommons.org/licenses/by-nc/2.0/
    * @author Etienne Gauvin
    * @version 1.4
    */
    
    
    /**
    * La langue utilis�e par d�faut.
    * The language used by default.
    * 
    * @var string "eng|fr|de|es"
    */
    
    define( 'ALLO_DEFAULT_LANG', 'eng' );
    
    
    /**
    * Activer les Exceptions
    * Enable Exceptions
    * 
    * @var bool
    */
    
    define( 'ALLO_THROW_EXCEPTIONS', true );
    
    
    /**
    * D�coder de l'UTF8 les donn�es r�ceptionn�es
    * Automatically decode the received data from UTF8
    * 
    * @var bool
    */
    
    define( 'ALLO_UTF8_DECODE', true );
    
    
    /**
    * Le partenaire par d�faut utilis� pour toutes les requ�tes.
    * The default partner used for all requests.
    * 
    * @var real|string
    */
    
    define( 'ALLO_DEFAULT_PARTNER', 3 );
    
    
    
    /**
    * La version de l'API Allocin� utilis�e par d�faut pour toutes les requ�tes.
    * The default version of the API Allocin� used for all requests.
    * 
    * @var real
    */
    
    define( 'ALLO_DEFAULT_VERSION', 3 );
    
    
    
    /**
    * Utiliser facilement l'API d'Allocin�
    * 
    * Les classes AlloHelper, AlloData et AlloImage permettent d'utiliser l'API d'Allocin� plus facilement.
    * 
    * @licence http://creativecommons.org/licenses/by-nc/2.0/
    * @author Etienne Gauvin
    * @version 1.1
    */
    
    class AlloHelper
    {
        
        /**
        * Contient la langue ('fr', 'eng', 'de', 'es')
        * @var string
        */
        
        private static $_sLanguage;
        
        /**
        * Pour retourner le langage et �ventuellement le modifier
        * 
        * @param string $newLanguage=null La nouvelle langue ('fr' | 'eng' | 'de' | 'es')
        * @param string $info=null L'info sur la langue � retourner ('site' | 'images')
        * @return string
        */
        
        public static function language( $newLanguage = null, $info = null )
        {
            $langs = array(
                'fr' => array(
                    'site' => "allocine.fr",
                    'images' => "images.allocine.fr"
                ),
                'es' => array(
                    'site' => "sensacine.com",
                    'images' => "imagenes.sensacine.com"
                ),
                'de' => array(
                    'site' => "filmstarts.de",
                    'images' => "bilder.filmstarts.de"
                ),
                'eng' => array(
                    'site' => "screenrush.co.uk",
                    'images' => "images.screenrush.co.uk"
                )
            );
            
            if ( !is_string(ALLO_DEFAULT_LANG) || !array_key_exists(ALLO_DEFAULT_LANG, $langs) )
            {
                exit('Invalid language value for const ALLO_DEFAULT_LANG (only "fr" or "eng" or "de" or "es").');
            }
            
            if ( $newLanguage !== null )
            {
                if ( !is_string($newLanguage) || !array_key_exists($newLanguage, $langs) )
                {
                    self::causesAnError('Invalid language value for param $newLanguage (only "fr" or "eng" or "de" or "es").');
                    self::$_sLanguage = ALLO_DEFAULT_LANG;
                }
                else self::$_sLanguage = $newLanguage;
            }
            
            if ( self::$_sLanguage === null )
                self::$_sLanguage = ALLO_DEFAULT_LANG;
            
            if ($info === null)
                return self::$_sLanguage;
            else
                return $langs[self::$_sLanguage][$info];
        }
        
        /**
        * Contient la derni�re erreur produite
        * @var array
        */
        
        private static $_lastError;
        
        
        /**
        * Provoquer une erreur
        * 
        * @param string $message Le message de l'erreur
        * @param int $code=0 Le code de l'erreur
        */
        
        public static function causesAnError( $message, $code = 0 )
        {
            if ( ALLO_THROW_EXCEPTIONS )
                throw ( $error = new ErrorException( $message, $code ));
            
            self::$_lastError = $error;
        }
        
        
        /**
        * R�cup�rer le message et le code de la derni�re erreur survenue
        * 
        * @return ErrorException|null
        */
        
        public static function lastError()
        {
            return self::$_lastError;
        }
        
        
        /**
        * Supprimer la derni�re erreur survenue
        * 
        */
        
        public static function clearErrors()
        {
            self::$_lastError = null;
        }
        
        
        /**
        * Contient le dernier URL utilis�
        * @var string
        */
        
        private $_sLastURL;
        
        
        /**
        * Retourne le dernier URL utilis�
        * 
        * @return string Le dernier URL utilis�
        */
        
        public function lastURL( )
        {
            return $this->_sLastURL;
        }
        
        
        /**
        * Cr�er un URL � partir de diff�rentes donn�es
        * 
        * @param string $type Le type de donn�es � r�cup�rer (exemple: "xml/movie")
        * @param array $options=array() Les options � ajouter dans l'URL sous la forme cl� => valeur 
        * @param string $language=null Pour sp�cifier �ventuellement un nouveau langage
        * @return string L'URL construit
        */
        
        public function creatURL( $type, $options=array(), $language=null )
        {
            $site = $this->language($language, 'site');
            $options_str = array();
            
            foreach ($options as $nom => $valeur) {
                if (is_string($nom))
                    $options_str[] = "$nom=$valeur";
                else
                    $options_str[] = (string) $valeur;
            }
            
            return "http://api.$site/$type?".implode('&',(array)$options_str);
        }
        
        
        /**
        * R�cup�rer des donn�es JSON depuis un URL gr�ce � file_get_contents() ou php_curl.
        * 
        * @param string $url L'URL vers lequel aller chercher les donn�es JSON.
        * @param bool $returnArray Retourner un tableau  plut�t qu'un objet AlloData pour contenir les donn�es.
        * @param bool $forceCURL Forcer l'utilisation de php_curl � la place de file_get_contents.
        * @return AlloData|array|false Un objet AlloData ou un array en cas de succ�s, false si une erreur est survenue.
        */
        
        public function getDataFromURL ( $url, $returnArray = false, $forceCURL = false )
        {
            if ( !function_exists("file_get_contents") || $forceCURL ) {
                if ( !function_exists("curl_init") ) {
                    $this->causesAnError("The extension php_curl must be installed with PHP or function file_get_contents must be enabled.");
                    return false;
                }
                else {
                    $curl = curl_init();
                    curl_setopt ($curl, CURLOPT_URL, $url);
                    curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($curl);
                    curl_close($curl);
                }
            }
            else {
                $data = @file_get_contents($url);
            }
            
            if (empty($data)) {
                $this->causesAnError("An error occurred while retrieving the data.");
                return false;
            }
            
            $data = @json_decode( $data, true );
            
            if (empty($data)) {
                $this->causesAnError("An error occurred when converting data.");
                return false;
            }
            else {
                if ($returnArray)
                    return (array) $data;
                else
                    return new AlloData( $data );
            }
        }
        
        
        /**
        * Pr�r�glages pour les param�tres d'URL
        */
        
        private $_aPresets = array();
        
        /**
        * Pr�r�glages automatiques pour les param�tres json, partner et version
        * 
        */
        
        public function autoPresets( )
        {
            $this->_aPresets['json'] = 1;
            
            if (empty($this->_aPresets['partner']))
                $this->_aPresets['partner'] = ALLO_DEFAULT_PARTNER;
            
            if (empty($this->_aPresets['version']))
                $this->_aPresets['version'] = ALLO_DEFAULT_VERSION;
        }
        
        
        /**
        * Ajouter/modifier des pr�r�glages
        * 
        * @param array|string $preset Si c'est un array alors chaque paire "cl�" => "valeur" ou "cl�=valeur" sera enregistr�e dans les pr�r�glages, sinon si c'est une cha�ne alors c'est le nom du pr�r�glage et $value est sa valeur.
        * @param string|int $value La valeur du pr�r�glage si $preset est une cha�ne de caract�res.
        */
        
        public function set( $preset, $value=null )
        {
            if (is_array( $preset ))
                foreach( $preset as $name => $value )
                    $this->_aPresets[ (string) $name ] = (string) $value;
            
            elseif (is_string( $preset ))
                $this->_aPresets[ $preset ] = (string) $value;
        }
        
        
        /**
        * Retourne les pr�r�glages
        * 
        * @param string|null $preset=null Indiquer le nom d'un pr�r�glage pour conna�tre sa valeur.
        * @return mixed Le tableau des pr�r�glages ou la valeur d'une option si $preset != null
        */
        
        public function getPresets( $preset = null )
        {
            if ($preset === null)
                return $this->_aPresets;
            else
                return @$this->_aPresets[$preset];
        }
        
        
        /**
        * Effacer les pr�r�glages
        * 
        * @param array $presets=array() Indiquer les pr�r�glages � effacer ou laisser vide pour tout effacer.
        * @param bool $inverse=false Si $inverse vaut true alors tous les pr�r�glages seront effac�s sauf ceux indiqu�s dans $presets.
        */
        
        public function clearPresets( $presets = array(), $inverse = false )
        {
            if (empty($presets))
                $this->_aPresets = array();
            else {
                if ($inverse)
                    foreach($this->_aPresets as $psn => $ps) {
                        if (!in_array($psn, $presets))
                            unset($this->_aPresets[$psn]);
                    }
                else
                    foreach($presets as $ps)
                        unset($this->_aPresets[$ps]);
            }
        }
        
        
        /**
        * Pour effectuer une requ�te sur un type de donn�es du nom de la m�thode donn�e si elle n'existe pas.
        * 
        * @param string $offset C'est le type de donn�es qui doit-�tre r�cup�r�, les _ seront remplac�s par des /
        * @param array $arg[0]=null Les �ventuels param�tres qu'il est possible de rajouter � l'URL
        * @return AlloData|false
        */
        
        public function __call( $offset, $arg )
        {
            $type = 'xml/' . str_replace('_', '/', $offset);
            
            $this->autoPresets();
            
            $this->set( @$arg[0] );
            $options = $this->getPresets();
            
            $this->_sLastURL = $url = $this->creatURL($type, $options);
            
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                if (empty($data['error'])) {
                    if (count($data) == 1)
                        return new AlloData( current($data) );
                    
                    else
                        return new AlloData( $data );
                }
                else {
                    $this->causesAnError( $data['error']['$'], $data['error']['code'] );
                    return new AlloData( $data );
                }
            }
        }
        
        
        /**
        * Effectuer une recherche sur Allocin�.
        * 
        * @param string $q La cha�ne de recherche.
        * @param real $count=10 Le nombre de r�sultats � renvoyer.
        * @param real $page=1 La page des r�sultats (en fonction de $count).
        * @param string|real $profile='medium' La quantit� d'informations � renvoyer sur chaque r�sultat: 1='small', 2='medium', 3='large'.
        * @param bool $sortMovies=false Trier les r�sultats des films en fonction de leur ressemblance avec la cha�ne de recherche et enregistre les r�sultats dans 'movieSorted' (distance de Levenshtein, peut ralentir sensiblement l'ex�cution)
        * @return array|false
        */
        
        public function search( $q, $count=10, $page=1, $profile='medium', $sortMovies=false )
        {
            
            // Traitement de la cha�ne de recherche
            if (!is_string($q) || strlen($q) < 2 )
            {
                $this->causesAnError( "The keywords should contain more than one character." );
                return false;
            }
            
            $accents = "��������������������������'";
            $normal  = 'aaaaaceeeeiiiinooooouuuuyy ';
            $q = utf8_encode(strtr(strtolower(trim($q)), $accents, $normal));
            
            // Enregistrement des pr�r�glages
            switch($profile)
            {
                case 1: $profile = 'small'; break;
                case 3: $profile = 'large'; break;
                default: $profile = 'medium';
            }
            
            $this->set(array(
                'q' => urlencode($q),
                'count' => (real) $count,
                'page' => (real) $page,
                'profile' => $profile
            ));
            
            $this->autoPresets();
            $options = $this->getPresets();
            $this->_sLastURL = $url = $this->creatURL('xml/search', $options);
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                if (empty($data['error'])) {
                
                    $data = $data['feed'];
                    
                    // R�organisation des films
                    if ($sortMovies && !empty($data['movie']))
                    {
                        $movies = $data['movie'];
                        $resultats = array();
                        
                        $sim = 0;
                        $best = 0;
                        
                        foreach ($movies as $i => $m)
                        {
                            $title = (string) @$m['title'];
                            
                            if ($title === '')
                                $title = $m['originalTitle'];
                            
                            $similitudes[$i] = levenshtein($q, strtr(strtolower($title), $accents, $normal));
                            
                        }
                        
                        asort($similitudes, true);
                        
                        foreach ($similitudes as $i => $sim) {
                            $resultats[] = $movies[$i];
                        }
                        
                        $data['movieSorted'] = $resultats;
                        $data['movie'] = $movies;
                    }
                    
                    // R�organisation des compteurs des r�sultats
                    if (!empty($data['results']))
                    {
                        foreach ($data['results'] as $r)
                            $data['results'][$r['type']] = (int) $r['$'];
                    }
                    
                    return new AlloData( $data );
                }
                else {
                    $this->causesAnError( $data['error']['$'], $data['error']['code'] );
                    return new AlloData( $data );
                }
            }
        }
        
        
        /**
        * Pour rechercher des informations sur les films
        * 
        * @param string|real $movie Si $movie est une cha�ne de caract�res alors une recherche sera effectu�e et le r�sultat le plus correspodant sera s�lectionn�. Si c'est un nombre alors les informations seront directement r�cup�r�es depuis cet identifiant.
        * @return AlloData|false
        */
        
        public function movie( $movie=null )
        {
            // Si $movie est une cha�ne � rechercher
            if (is_string( $movie ))
            {
                $search = $this->search( $movie, 10, 1, 'small', true, array() );
                if (empty($search) || empty($search->movie[0]))
                {
                    $this->causesAnError('No result for "'.$movie.'"');
                    $this->clearPresets(array('partner', 'version'), true);
                    return false;
                }
                else
                {
                    $this->clearPresets(array('partner', 'version'), true);
                    $this->set( 'code', $search->movie[0]->code );
                }
            }
            
            // Si $movie est un tableau d'options
            elseif (is_array( $movie ))
            {
                $this->set( $movie );
            }
            
            // Si $movie est un identifiant
            elseif (is_numeric( $movie ))
            {
                $this->set( 'code', $movie );
            }
            
            // Sinon
            else
            {
                $this->causesAnError( 'Invalid format for the parameter $movie.' );
                return false;
            }
            
            $this->autoPresets();
            $options = $this->getPresets();
            $this->_sLastURL = $url = $this->creatURL('xml/movie', $options);
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                if (empty($data['error'])) {
                    if (!empty($data['movie']))
                    {
                        $return = new AlloData($data['movie']);
                        
                        if (empty($return->title))
                            $return->title = $return->originalTitle;
                        
                        return $return;
                    }
                    else
                        return new AlloData( $data );
                }
                else {
                    $this->causesAnError( $data['error']['$'], $data['error']['code'] );
                    return new AlloData( $data );
                }
            }
        }
        
        /**
        * Retourne une liste de films selon diff�rents crit�res
        * 
        * @param string|real $filter Filtre de la liste de films.
        * @param string|real $orderby='dateasc' Modifier l'ordre des films.
        * @param real $count=10 Le nombre de r�sultats � renvoyer.
        * @param real $page=1 La page des r�sultats (en fonction de $count).
        * @param string|real $profile='medium' La quantit� d'informations � renvoyer sur chaque r�sultat: 1='small', 2='medium', 3='large'.
        * @return AlloData|false
        */
        
        public function movielist( $filter, $orderby='dateasc', $count=10, $page=1, $profile='medium' )
        {
            $this->set(array(
                'filter' => $filter,
                'orderby' => $orderby,
                'count' => (real) $count,
                'page' => (real) $page,
                'profile' => $profile
            ));
            
            $this->autoPresets();
            $options = $this->getPresets();
            $this->_sLastURL = $url = $this->creatURL('xml/movielist', $options);
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                if (empty($data['error'])) {
                    if (count($data) == 1)
                        return new AlloData( current($data) );
                    else
                        return new AlloData( $data );
                }
                else {
                    $this->causesAnError( $data['error']['$'], $data['error']['code'] );
                    return new AlloData( $data );
                }
            }
        }
        
        /**
        * Retourne la liste des films favoris d'un utilisateur
        * 
        * @param string $hash La cl� de connexion fournie par AlloHelper::connect()
        * @param string|real $filter Filtre de la liste de films.
        * @param string|real $orderby='dateasc' Modifier l'ordre des films.
        * @param real $count=10 Le nombre de r�sultats � renvoyer.
        * @param real $page=1 La page des r�sultats (en fonction de $count).
        * @param string|real $profile='medium' La quantit� d'informations � renvoyer sur chaque r�sultat: 1='small', 2='medium', 3='large'.
        * @return AlloData|false
        */
        
        public function myMovielist( $hash, $filter='', $orderby='dateasc', $count=10, $page=1, $profile='medium' )
        {
            $this->set(array(
                'h' => $hash,
                'filter' => $filter,
                'orderby' => $orderby,
                'count' => (real) $count,
                'page' => (real) $page,
                'profile' => $profile
            ));
            
            $this->autoPresets();
            $options = $this->getPresets();
            $this->_sLastURL = $url = $this->creatURL('xml/my/movielist', $options);
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                if (empty($data['error'])) {
                    if (count($data) == 1)
                        return new AlloData( current($data) );
                    else
                        return new AlloData( $data );
                }
                else {
                    $this->causesAnError( $data['error']['$'], $data['error']['code'] );
                    return new AlloData( $data );
                }
            }
        }
        
        /**
        * Pour connecter un utilisateur et r�cup�rer une cl� de connection
        * 
        * @param string $log Nom de l'utilisateur.
        * @param string $pwd Mot de passe de l'utilisateur.
        * @return string|false La cl� de connection ou false en cas d'erreur.
        */
        
        public function connect( $log, $pwd )
        {
            $this->set( 'log', (string) $log );
            $this->set( 'pwd', (string) $pwd );
            
            $this->autoPresets();
            $options = $this->getPresets();
            $this->_sLastURL = $url = $this->creatURL('xml/my/connect', $options);
            $data = $this->getDataFromURL($url, true);
            
            if (empty($data))
                return false;
            else
            {
                // Cl� de connexion
                if ( !empty($data['connect']['h']) )
                {
                    $this->clearPresets();
                    $this->set( 'h', $data['connect']['h'] );
                    return $data['connect']['h'];
                }
                
                // Erreur signal�e par Allocin�
                elseif ( @$data['connect']['code'] != 0)
                {
                    $this->causesAnError( $data['connect']['$'], $data['connect']['code'] );
                    return false;
                }
                
                // Erreur non identifi�e
                else
                {
                    $this->causesAnError( "Connection error." );
                    return false;
                }
            }
        }
        
        
    }
    
    
    
    /**
    * Manipuler facilement les donn�es re�ues.
    */
    
    class AlloData implements ArrayAccess, Iterator
    {
        
        /**
        * Contiendra les donn�es
        * @var array
        */
        
        private $_data = array();
        
        
        /**
        * Valeur par d�faut pour les propri�t�s inexistantes
        */
        
        private $_defaultValue = null;
        
        
        /**
        * Modifier $this->_defaultValue
        * @param mixed $defaultValue La nouvelle valeur
        */
        
        public function setDefaultValue( $defaultValue )
        {
            $this->_defaultValue = $defaultValue;
        }
        
        
        /**
        * Retourne la valeur de $this->_defaultValue
        */
        
        public function getDefaultValue( )
        {
            return $this->_defaultValue;
        }
        
        
		/**
		* Valeur de remplacement pour les symboles '$' ou false pour ne rien modifier.
		*/
		
		private $_dollars = 'value';
		
		/**
		* Modifier $this->_dollars
		* @param string $_dollars La nouvelle valeur
		*/
		
		public function setDollars( $convertDollars )
        {
			$this->_dollars = $convertDollars;
		}
		
		/**
		* Retourne la valeur de $this->_dollars
		*/
		
		public function getDollars( )
        {
			return $this->_dollars;
		}
		
		/**
		* D�coder une variable depuis l'UTF8.
		* 
		* @param array|string $var La variable peut-�tre un tableau ou une cha�ne.
		* @return array|string Le tableau|la cha�ne d�cod�(e)
		*/
		
		public function utf8_decode($var)
        {
			if (is_string($var)) return utf8_decode(str_replace('’', "'", $var));
            elseif (!is_array($var)) return $var;
            
            $return = array();
            
			foreach ($var as $i => $cell)
				$return[utf8_decode($i)] = self::utf8_decode($cell);
			
			return $return;
		}
		
        
        /**
        * Constructeur
        */
        
        public function __construct( $data, $defaultValue = null, $dollars = 'value' )
        {
            $this->_data = (array) $data;
            $this->_defaultValue = $defaultValue;
            $this->_dollars = $dollars;
        }
        
        
        /**
        * Retourne une valeur existante dans les donn�es enregistr�es
        * 
        */
        
        public function get( $returnArray = false, $offset = null, $noException = ALLO_THROW_EXCEPTIONS, &$isset = null )
        {
            
            $returnArray = (bool) $returnArray;
            $data = &$this->_data;
            
            if ( $offset === null )
            {
                if ($returnArray)
                {
                    if (ALLO_UTF8_DECODE)
                        return self::utf8_decode($data);
                    else
                        return $data;
                }
                
                else
                    return new AlloData($data, $this->_defaultValue, $this->_dollars );
            }
            
            else
            {
                if (isset( $data[$offset] ))
                {
                    $isset = true;
                    if (is_array( $data[$offset] ) && $returnArray === false)
                        return new AlloData( $data[$offset], $this->_defaultValue, $this->_dollars );
                    
                    elseif ( ALLO_UTF8_DECODE && is_string( $data[$offset] ))
                        return utf8_decode($data[$offset]);
                    
                    else
                        return $data[$offset];
                }
                elseif ( $offset == $this->_dollars && isset($data['$']) )
                {
                    return utf8_decode($data['$']);
                }
                else
                {
                    $isset = false;
                    if (!$noException)
                        AlloHelper::causesAnError("This offset ($offset) does not exist.");
                    return $this->_defaultValue;
                }
            }
            
        }
        
        
        /**
        * Si l'on essaie d'acc�der � une propri�t� inexistante
        * 
        */
        
        public function __get( $offset )
        {
            return $this->get(false, $offset);
        }
        
        
        /**
        * Retourne les donn�es sous forme d'un array
        * 
        */
        
        public function getArray()
        {
            if (ALLO_UTF8_DECODE)
                return (array) self::utf8_decode($this->_data);
            else
                return (array) $this->_data;
        }
        
        /**
        * Si l'on veut de cr�er/modifier une propri�t�
        * 
        */
        
        public function __set( $offset, $value )
        {
            return $this->_data[$offset] = $value;
        }
        
        /**
        * Si l'on essaie d'acc�der � une m�thode inexistante
        * 
        */
        
        public function __call( $offset, $args )
        {
            if (empty($args[0]))
                return $this->get(false, $offset);
            
            else
            {
                $type = (string) $args[0];
                $data = $this->get(true, $offset);
                
                // Nouvelle Image
                if ($type === 'img')
                    return new AlloImage($data);
                
                elseif ($type === 'sec')
                {
                    $data = (real) $data;
                    
                    $periods = array(
                        'hours' => 3600,
                        'minutes' => 60,
                        'seconds' => 1
                    );

                    $durations = array();

                    foreach ($periods as $period => $seconds_in_period) {
                        if ($data >= $seconds_in_period) {
                            $durations[$period] = (int) floor($data / $seconds_in_period);
                            $data -= $durations[$period] * $seconds_in_period;
                        }
                    }
                    
                    $runtime = new AlloData($durations, 0);
                    $runtime->time = "{$runtime->hours}h{$runtime->minutes}";
                    
                    return $runtime;
                    // @see http://be2.php.net/manual/fr/ref.datetime.php#78025
                }
                
            }
        }
        
        /**
        * Pointeur interne
        * @var real
        */
        
        private $_position = 0;
        
        /**
        * Pour parcourir facilement les donn�es comme un tableau
        * 
        * @return mixed Les donn�es de l'index courant
        */
        
        public function current( )
        {
            return $this->get(false, $this->_position, true);
        }
        
        /**
        * Pour parcourir facilement les donn�es comme un tableau
        * 
        * @return bool True si les donn�es existe, false si non.
        */
        
        public function valid( )
        {
            $this->get(false, $this->_position, true, $isset);
            return $isset;
        }
        
        /**
        * Pour parcourir facilement les donn�es comme un tableau
        * 
        * @return real La position actuelle
        */
        
        public function key( )
        {
            return $this->_position;
        }
        
        /**
        * Pour parcourir facilement les donn�es comme un tableau
        * 
        */
        
        public function next( )
        {
            $this->_position++;
        }
        
        /**
        * Pour parcourir facilement les donn�es comme un tableau
        * 
        * @param &$var=null Le nombre auquel initialiser le pointeur
        */
        
        public function rewind( )
        {
            $this->_position = 0;
        }
        
        
        /**
        * Compter le nombre d'�l�ment dans le tableau des donn�es
        * 
        * @return (real)
        */
        
        public function _count( )
        {
            return count($this->data);
        }
        
        /**
        * Si l'on essaie d'acc�der � l'objet comme � un tableau
        * 
        */
        
        public function offsetGet( $offset )
        {
            return $this->get(false, $offset);
        }
        
        /**
        * Si l'on veut de cr�er/modifier une propri�t�
        */
        
        public function offsetSet( $offset, $value )
        {
            return $this->_data[$offset] = $value;
        }
        
        /**
        * Lors de la v�rification de l'existence d'une propri�t� avec isset
        */
        
        public function offsetExists( $offset )
        {
            return ($this->get(false, $offset, true) !== $this->_defaultValue);
        }
        
        /**
        * Il n'est pas autoris� de d�truire une propri�t�
        */
        
        public function offsetUnset($offset)
        {
            return;
        }
    }
    
    
    
    /**
    * Manipuler facilement URL des images sur Allocin�.
    */
    
    class AlloImage
    {
        /**
        * Contiendra l'URL de l'image
        * @var string
        */
        
        private $url;
        
        
        /**
        * Tableau contenant les diff�rents param�tres qu'il est possible de passer � l'URL
        * @var array
        */
        
        private $settings = array( 'icon'=>false, 'border'=>false, 'size'=>false );
        
        /**
        * Le langage
        * @var string
        */
        
        private $language;
        
        /**
        * Le r�pertoire de l'image
        * @var string
        */
        
        private $image;
        
        /**
        * Le r�pertoire de l'image par d�faut
        * @var string
        */
        
        private $default_image = "/commons/emptymedia/AffichetteAllocine.gif";
        
        /**
        * Modifier l'ic�ne.
        * Attention l'ic�ne n�2 ('overlayVod120.png') redimensionne l'image automatiquement.
        * 
        * @param string $position='c' Initiales de la position de l'icone sur l'image. (n, e, s, w, il est possible de les combiner, c pour centrer).
        * @param int $margin La marge entre l'ic�ne et le bord de l'image.
        * @param real|string $icon=0 Le num�ro de l'ic�ne ou son nom. Voir la documentation pour plus de d�tails.
        * @var string
        */
        
        public function icon($position='c', $margin=4, $icon=0)
        {
            switch ($icon)
            {
                case 0:
                $icon = 'play.png';
                break;
                
                case 1:
                $icon = 'overplay.png';
                break;
                
                case 2:
                $icon = 'overlayVod120.png';
                $this->resize(120, 160);
                break;
            }
            
            $this->settings['icon'] = array(
                'position' => substr($position, 0, 2),
                'margin' => (real) $margin,
                'icon' => (string) $icon
            );
            
            return $this;
        }
        
        
        /**
        * Retourne les param�tres de l'ic�ne ou false si il n'y en a pas.
        * 
        * @return array|false Les param�tres enregistr�s ou false si il n'y a pas d'ic�ne
        */
        
        public function getIcon()
        {
            return $this->settings['icon'];
        }
        
        
        /**
        * Efface l'ic�ne.
        * 
        * @return AlloImage
        */
        
        public function noIcon()
        {
            $this->settings['icon'] = false;
            return $this;
        }
        
        
        /**
        * Modifier la bordure
        * 
        * @param real $size=1 La largeur de la bordure.
        * @param real $size='000000' La couleur de la bordure au format RGB h�xad�cimal.
        * @return AlloImage
        */
        
        public function border($size=1, $color="000000")
        {
            $this->settings['border'] = array(
                'size' => (real) $size,
                'color' => (string) $color
            );
            return $this;
        }
        
        
        /**
        * Retourne les param�tres de la bordure ou false si il n'y en a pas.
        * 
        * @return array|false
        */
        
        public function getBorder()
        {
            return $this->settings['border'];
        }
        
        
        /**
        * Efface la bordure
        * 
        * @return AlloImage
        */
        
        public function noBorder()
        {
            $this->settings['border'] = false;
            return $this;
        }
        
        
        /**
        * Redimensionner l'image en fonction du plus petit param�tre.
        * 
        * @param real|string $xmax='x' La largeur maximale de l'image. Mettre une cha�ne telle que 'x' pour une taille automatique en fonction de $ymax.
        * @param real|string $ymax='y' La hauteur maximale de l'image. Mettre une cha�ne telle que 'y' pour une taille automatique en fonction de $xmax.
        * @return AlloImage
        */
        
        public function resize($xmax='x', $ymax='y')
        {
            $this->settings['size'] = array(
                'method' => 'r',
                'xmax' => $xmax,
                'ymax' => $ymax
            );
            return $this;
        }
        
        /**
        * Recouper l'image en fonction des deux param�tres.
        * 
        * @param real $xmax La largeur de l'image.
        * @param real $ymax La hauteur de l'image.
        * @return AlloImage
        */
        
        public function cut($xmax, $ymax)
        {
            $this->settings['size'] = array(
                'method' => 'c',
                'xmax' => (real) $xmax,
                'ymax' => (real) $ymax
            );
            return $this;
        }
        
        
        /**
        * Retourne les param�tres de la taille de l'image ou false si il n'y en a pas.
        * 
        * @return array|false
        */
        
        public function getSize()
        {
            return $this->settings['size'];
        }
        
        
        /**
        * Efface les param�tres de taille pour retrouver une image normale.
        * 
        * @return AlloImage
        */
        
        public function maxSize()
        {
            $this->settings['size'] = false;
            return $this;
        }
        
        
        /**
        * Retourne le language sous forme de 2 ou 3 lettres.
        * 
        * @see AlloHelper::language()
        * @return string
        */
        
        public function getLanguage()
        {
            return AlloHelper::language();
        }
        
        
        /**
        * Modifier le language.
        * 
        * @see AlloHelper::language()
        * @return AlloImage
        */
        
        public function setLanguage($lang)
        {
            $this->language = AlloHelper::language($lang);
            return $this;
        }
        
        
        /**
        * Fournir l'URL � parser pour cr�er l'objet AlloImage.
        * 
        * @param string $url Un URL vers une image sur Allocin�
        */
        
        public function __construct( $url )
        {
            if (is_string($url) && substr($url, 0, 4) === 'http')
            {
                $this->url = $url;
                $this->urlExplode();
            }
            else
                AlloHelper::causesAnError("This is not a link to an image.");
            
        }
        
        
        /**
        * Parser l'URL et r�partir les diff�rents param�tres.
        * 
        * @param string $url Un URL vers une image sur Allocin�.
        * @return bool
        */
        
        private function urlExplode()
        {
            if (empty($this->url))
                return false;
            
            if (preg_match("#^http://(.+)/?(.*)(/medias/.*|/commons/.*)#", $this->url, $matches) == false) {
                AlloHelper::causesAnError("This is not a link to an image.");
                return false;
            }
            
            $params = explode('/', $matches[2]);
            
            $this->language = $matches[1];
            $this->image = $matches[3];
            
            $newsize = false;
            
            foreach($params as $p) {
                if (preg_match("#^o_(.+)_(.+)_(.+)$#i", $p, $i) != false) {
                    $this->icon($i[3], $i[2], $i[1]);
                }
                elseif (preg_match("#^b[xy]?_([0-9]+)_([0-9a-f]{6}|.*)$#i", $p, $i) != false) {
                    if (preg_match("#^[0-9a-f]{6}$#i", $i[2]) == false) $i[2] = "000000";
                    $this->border($i[1], $i[2]);
                }
                elseif (preg_match("#^r[xy]?_([0-9]+|[a-z0-9]+)_([0-9]+|[a-z0-9]+)$#i", $p, $i) != false) {
                    $this->resize((real) $i[1], (real) $i[2]);
                }
                elseif (preg_match("#^c[xy]?_([0-9]+|[a-z0-9]+)_([0-9]+|[a-z0-9]+)$#i", $p, $i) != false) {
                    $this->cut((real) $i[1], (real) $i[2]);
                }
            }
            
            $this->language = AlloHelper::language();
            
            return true;
        }
        
        
        /**
        * Retourne l'URL construit � partir des diff�rents param�tres.
        * 
        * @return string L'URL construit.
        */
        
        public function url()
        {
            $size = ($this->settings['size']===false) ? '' : "/{$this->settings['size']['method']}_{$this->settings['size']['xmax']}_{$this->settings['size']['ymax']}";
            $border = ($this->settings['border']===false) ? '' : "/b_{$this->settings['border']['size']}_{$this->settings['border']['color']}";
            $icon = ($this->settings['icon']===false) ? '' : "/o_{$this->settings['icon']['icon']}_{$this->settings['icon']['margin']}_{$this->settings['icon']['position']}";
            $lang = AlloHelper::language($this->language, 'images');
            
            return "http://" . (string) preg_replace('#/+#', '/', "{$lang}/{$size}{$border}{$icon}{$this->image}");
        }
        
        
        /**
        * Alias de AlloImage::url()
        * 
        * @return string L'URL construit.
        */
        
        public function __toString()
        {
            return $this->url();
        }
        
    }

