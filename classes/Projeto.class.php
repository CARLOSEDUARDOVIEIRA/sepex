<?php

/**
 * Classe para definicoes do projeto
 *
 * @author Carlos Eduardo Vieira
 */
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');

class Projeto {

    protected $area_curso;
    protected $categoria;
    protected $cod_projeto;
    protected $data_cadastro;
    protected $email;
    protected $mesa;
    protected $periodo;
    protected $resumo;
    protected $status;
    protected $tags;
    protected $turno;
    protected $titulo;

    function __construct($dados) {
        global $DB;
        
        $this->data_cadastro = $dados->data_cadastro;
        $this->cod_projeto = (string) $dados->cod_projeto;
        $this->titulo = (string) $dados->titulo;
        $this->resumo = (string) $dados->resumo;
        $this->email = $dados->email;
        $this->tags = (string) $dados->tags;
        $this->periodo = (int) $dados->periodo;
        $this->turno = (string) $dados->turno;
        $this->area_curso = (int) $dados->area_curso;
        $this->mesa = (int) $dados->aloca_mesa;
        $this->categoria = (int) $dados->cod_categoria;
        $this->status = null;
        return $this;
    }

}
