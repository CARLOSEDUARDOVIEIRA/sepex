<?php

/**
 * Relatorio de avaliacao personalizado
 *
 * @author Carlos Eduardo Vieira
 */
class ReportProfessores extends table_sql {

    private $id;

    function __construct($uniqueid, $id) {
        parent::__construct($uniqueid);
        $this->id = $id;
    }

    function col_button($values) {
        if ($this->is_downloading()) {
            return $values->codprojeto;
        }

        if ($values->tipo == 'Avaliador') {
            return '<a href="./avaliador.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&idcategoria=' . $values->idcategoria . '">' . "Avaliar</a>";
        } else {
            return '<a href="./orientador.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&idcategoria=' . $values->idcategoria . '">' . "Avaliar</a>";
        }
    }

    function col_tipo($values) {
        return $values->tipo;
    }

    function col_categoria($values) {
        $constantes = new Constantes();
        return $constantes->detailCategorias($values->idcategoria);
    }

    function col_curso($values) {
        $const = new Constantes();
        return $const->detailCursos($values->idcurso);
    }

    function col_titulo($values) {
        if ($this->is_downloading()) {
            return $values->titulo;
        }

        if ($values->tipo == 'Avaliador') {
            return '<a href="./avaliador.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&idcategoria=' . $values->idcategoria . '">' . $values->titulo . '</a>';
        } else {
            return '<a href="./orientador.php?id=' . $this->id . '&idprojeto=' . $values->idprojeto . '&idcategoria=' . $values->idcategoria . '">' . $values->titulo . '</a>';
        }
    }

    function col_alunos($values) {
        $alunocontroller = new AlunoController();
        return implode(", ", $alunocontroller->getNameAlunos($values->idprojeto));
    }

    function col_nomelocalapresentacao($values) {
        $apresentacaocontroller = new ApresentacaoController();
        $local = $apresentacaocontroller->detailApresentacao($values->idprojeto)->nomelocalapresentacao;
        if ($local) {
            return $local;
        } else {
            return 'Aguardando definiçao';
        }
    }

    function col_dtapresentacao($values) {
        $apresentacaocontroller = new ApresentacaoController();
        $date = $apresentacaocontroller->detailApresentacao($values->idprojeto)->dtapresentacao;
        if ($date) {
            return date("d/m/Y H:i:s", $date);
        }
        return 'Aguardando definiçao';
    }

    function col_notafinal($values) {

        if ($values->tipo == 'Avaliador') {
            if ($values->notafinal) {
                return $values->notafinal / 2;
            } else {
                return $values->notafinal;
            }
        } else {
            if ($values->statusresumo == 0) {
                return get_string('reprovado', 'sepex');
            } elseif ($values->statusresumo == 1) {
                return get_string('aprovado', 'sepex');
            } elseif ($values->statusresumo == 2) {
                return get_string('emanalise', 'sepex');
            }
        }
    }

}
