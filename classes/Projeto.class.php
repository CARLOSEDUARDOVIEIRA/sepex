<?php

/**
 * Classe para definicoes do projeto
 *
 * @author Carlos Eduardo Vieira
 */

class Projeto {
    
    protected $id_projeto;
    protected $area_curso;
    protected $cod_categoria;
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
        $this->cod_categoria = (int) $dados->cod_categoria;
        $this->status = null;
        return $this;
    }

}
