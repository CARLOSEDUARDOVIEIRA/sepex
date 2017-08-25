<?php

/**
 * Classe para definicoes do projeto
 *
 * @author Carlos Eduardo Vieira
 */
class Projeto {

    protected $idprojeto;
    protected $areacurso;
    protected $idcategoria;
    protected $codprojeto;
    protected $dtcadastro;
    protected $email;
    protected $alocamesa;
    protected $idperiodo;
    protected $resumo;
    protected $statusresumo;
    protected $obsorientador;
    protected $tags;
    protected $turno;
    protected $titulo;
    protected $idcurso;

    function __construct($dados) {
        $this->areacurso = (int) $dados->areacurso;
        $this->idcategoria = (int) $dados->idcategoria;
        $this->codprojeto = (string) $dados->codprojeto;
        $this->dtcadastro = (string)$dados->dtcadastro;
        $this->email = $dados->email;
        $this->alocamesa = (int) $dados->alocamesa;
        $this->idperiodo = (int) $dados->idperiodo;
        $this->resumo = (string) $dados->resumo;
        $this->statusresumo = null;
        $this->obsorientador = null;
        $this->tags = (string) $dados->tags;
        $this->turno = (string) $dados->turno;
        $this->titulo = (string) $dados->titulo;
        $this->idcurso = (int)$dados->idcurso;
        return $this;
    }

}
