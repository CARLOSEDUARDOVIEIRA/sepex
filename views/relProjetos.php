<?php

/* PAGINA DE EXIBICAO DOS RELATORIOS DOS PROJETOS */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../classes/FormularioPesquisa.class.php';
require '../controllers/ProjetoController.class.php';
require '../constantes/Constantes.class.php';
require '../controllers/ProfessorController.class.php';

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}

require_login($course, true, $cm);

$PAGE->set_url('/mod/sepex/views/relProjetos.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('header_relatorios', 'sepex'), 3);

$filtro = new FormularioPesquisa("relProjetos.php?id={$id}");
$filtro->display();

$projetocontroller = new ProjetoController();
$constantes = new Constantes();
$professorcontroller = new ProfessorController();

if ($filtro->is_cancelled()) {
    redirect("../view.php?id={$id}");
}

if (!empty($dados = $filtro->get_data())) {

    $consulta = "1 = 1";

    if ($dados->idcurso) {
        $consulta = $consulta . ' AND idcurso = ' . "'" . $dados->idcurso . "'";
    }

    if ($dados->areacurso) {
        $consulta = $consulta . ' AND areacurso = ' . $dados->areacurso;
    }
    if ($dados->alocamesa != null) {
        $consulta = $consulta . ' AND alocamesa = ' . $dados->alocamesa;
    }
    if ($dados->turno != null) {
        $consulta = $consulta . ' AND turno = ' . "'" . $dados->turno . "'";
    }
    if ($dados->idcategoria) {
        $consulta = $consulta . ' AND idcategoria = ' . $dados->idcategoria;
    }

    if ($dados->statusresumo == 2) {
        $consulta = $consulta . ' AND statusresumo is null';
    } elseif ($dados->statusresumo != null) {
        $consulta = $consulta . ' AND statusresumo = ' . $dados->statusresumo;
    }


    $projetos = $projetocontroller->getProjetosFiltrados($consulta);
    echo get_string('numeroregistros', 'sepex', count($projetos));
    echo '<br><br>';
    echo '<hr>';
    $exportar = html_writer::start_tag('a', array('href' => "./exportarRelatorio.php?id={$id}&consulta={$consulta}",));
    $exportar .= html_writer::start_tag('img', array('src' => '../pix/export.png'));
    $exportar .= ' ' . get_string('exportar_dados', 'sepex');
    $exportar .= html_writer::end_tag('a');
    echo $exportar;
    echo '<hr>';

    $relavaliacao = html_writer::start_tag('a', array('href' => "./relAvaliacao.php?id={$id}&consulta={$consulta}",));
    $relavaliacao .= html_writer::start_tag('img', array('src' => '../pix/relnotas.png'));
    $relavaliacao .= ' ' . get_string('rel_avaliacao', 'sepex');
    $relavaliacao .= html_writer::end_tag('a');
    echo $relavaliacao;
    echo '<hr>';
    
}

echo $OUTPUT->footer();
