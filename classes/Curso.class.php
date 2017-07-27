<?php

/**
 * Description of CursoProjeto
 *
 * @author Carlos Eduardo Vieira
 */

class Curso {
    public $id_curso;
    public $nome_curso;
    
    function __construct($id_curso, $nome_curso) {
        $this->id_curso = $id_curso;
        $this->nome_curso = $nome_curso;
    }

}
