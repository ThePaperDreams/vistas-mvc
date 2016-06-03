<?php

class Controller
{
    protected $plantilla = "basica";
    protected $rutaPlantillas;
    protected $rutavistas;
    protected $contenido = "";
    protected $titulo = "";

    /**
     * @var null Database Connection
     */
    public $db = null;

    /**
     * @var null Model
     */
    public $model = null;

    /**
     * Whenever controller is created, open a database connection too and load "the model".
     */
    function __construct()
    {
        $this->openDatabaseConnection();
        $this->loadModel();
        #construimos las rutas de las vistas y los temas
        $this->construirRutas();
        $this->titulo = get_called_class();
    }

    private function construirRutas(){
        $nombreCtrl = get_called_class();
        $this->rutavistas = realpath(APLICACION . "/vistas/" . lcfirst($nombreCtrl));
        $this->rutaPlantillas = realpath(APLICACION . "/vistas/plantillas/");        
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection()
    {
        // set the (optional) options of the PDO connection. in this case, we set the fetch mode to
        // "objects", which means all results will be objects, like this: $result->user_name !
        // For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
        // @see http://www.php.net/manual/en/pdostatement.fetch.php
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        // generate a database connection, using the PDO connector
        // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
    }

    /**
     * Loads the "model".
     * @return object model
     */
    public function loadModel()
    {
        require APP . 'model/model.php';
        // create new "model" (and pass the database connection)
        $this->model = new Model($this->db);
    }

    public function vista($vista, $params = []){
        $this->validarRutas($vista);
        ob_start();
        foreach($params AS $attr=>$v){ $$attr = $v; }
        include $this->rutavistas . DS . "$vista.php";
        $this->contenido = ob_get_clean();
        $this->cargarPlantilla();
    }

    private function validarRutas($vista){
        if(!$this->rutavistas){
            throw new Exception("No existe el directorio de vistas", 1);            
        } else if(!$this->rutaPlantillas){
            throw new Exception("No existe el directorio de plantillas", 1);            
        } else if(!file_exists($this->rutavistas . DS . "$vista.php")){
            throw new Exception("No existe la vista solicitada '$vista'", 1);            
        }
    }

    private function cargarPlantilla(){
        if(!file_exists($this->rutaPlantillas . DS . "$this->plantilla.php")){
            throw new Exception("No existe la plantilla '$this->plantilla'", 1);            
        }
        ob_start();
        include $this->rutaPlantillas . DS . "$this->plantilla.php";
        echo ob_get_clean();
    }
}
