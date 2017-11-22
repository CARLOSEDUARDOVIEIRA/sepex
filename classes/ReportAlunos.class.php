<?php

/**
 * Relatorio dos projetos cadastrados pelos alunos
 *
 * @author Carlos Eduardo Vieira
 */
class ReportAlunos extends table_sql {

    private $showactivity;
    private $id;

    function __construct($uniqueid, $id, $showactivity) {
        parent::__construct($uniqueid, $id);
        $this->showactivity = $showactivity;
        $this->id = $id;
    }

    function col_chat() {
        if (!$this->is_downloading()) {
            global $USER;
            return '<a href="../../../message/index.php?id=' . $this->id . '&user=' . $USER->id . '">' . "<img src='../pix/chat.png'></a>";
        }
    }

    function col_edit($values) {
        if (!$this->is_downloading()) {
            return '<a href="./cadastroProjeto.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&update=1' . '">' . "<img src='../pix/edit.png'></a>";
        }
    }

    function col_delete($values) {
        if (!$this->is_downloading()) {
            return '<a href="./cadastroProjeto.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&delete=1' . '">' . "<img src='../pix/delete.png'></a>";
        }
    }

    function col_view($values) {
        if (!$this->is_downloading()) {
            return '<a href="./projetoAluno.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '">' . "Visualizar</a>";
        }
    }

    function col_codprojeto($values) {
        return $values->codprojeto;
    }

    function col_titulo($values) {
        if ($this->is_downloading()) {
            return $values->titulo;
        }
        if ($this->showactivity) {
            return '<a href="./cadastroProjeto.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&update=1' . '">' . $values->titulo . '</a>';
        } else {
            return '<a href="./projetoAluno.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '">' . $values->titulo . '</a>';
        }
    }

    function col_dtcadastro($values) {
        return $values->dtcadastro;
    }

    function col_idcategoria($values) {
        $constantes = new Constantes();
        return $constantes->detailCategorias($values->idcategoria);
    }

    function col_curso($values) {
        $const = new Constantes();
        return $const->detailCursos($values->idcurso);
    }

    function col_alunos($values) {
        $alunocontroller = new AlunoController();
        return implode(", ", $alunocontroller->getNameAlunos($values->idprojeto));
    }

    function col_statusresumo($values) {
        if (!$this->showactivity) {
            if ($values->statusresumo == 0) {
                return get_string('reprovado', 'sepex');
            } elseif ($values->statusresumo == 1) {
                return get_string('aprovado', 'sepex');
            } elseif ($values->statusresumo == 2) {
                return get_string('emanalise', 'sepex');
            }
        }
    }

    function col_nomelocalapresentacao($values) {
        if (!$this->showactivity) {

            $apresentacaocontroller = new ApresentacaoController();
            $local = $apresentacaocontroller->detailApresentacao($values->idprojeto)->nomelocalapresentacao;
            if ($local) {
                return $local;
            } else {
                return 'Aguardando definiçao';
            }
        }
    }

    function col_dtapresentacao($values) {
        if (!$this->showactivity) {
            $apresentacaocontroller = new ApresentacaoController();
            $date = $apresentacaocontroller->detailApresentacao($values->idprojeto)->dtapresentacao;
            if ($date) {
                return date("d/m/Y H:i:s", $date);
            }
            return 'Aguardando definiçao';
        }
    }

}
