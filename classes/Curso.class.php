<?php

/**
 * Description of CursoProjeto
 *
 * @author Carlos Eduardo Vieira
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

class Curso {
    
    
    public $idcurso;
    public $nomecurso;
    public $areacurso;
    
    function __construct($data) {
        global $DB;
        
        $this->nomecurso = $data->nomecurso;
        $this->areacurso = $data->areacurso;
        $DB->insert_record("sepex_curso", $this);
    }

}
