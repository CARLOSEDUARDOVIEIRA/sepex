<?php

/* EXIBE A TELA PARA AVALIAÇÃO DO PROJETO PELOS PROFESSORES ORIENTADORES
 */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require('../classes/FormularioOrientador.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../controllers/AlunoController.class.php');
require ('../controllers/ProfessorController.class.php');
require ('../classes/SendMessage.class.php');
require ('../constantes/Constantes.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = optional_param('idprojeto', 0, PARAM_INT);

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

$modcontext = context_module::instance($cm->id);

$PAGE->set_url('/mod/sepex/views/orientador.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
define('VIEW_URL_LINK', "../view.php?id=" . $id);

echo $OUTPUT->header();

$projetocontroller = new ProjetoController();
$projeto = $projetocontroller->detail($idprojeto);
$alunocontroller = new AlunoController();

$avaliacao = new FormularioOrientador("orientador.php?id={$id}&idprojeto={$idprojeto}", array('modcontext' => $modcontext, 'resumo' => $projeto[$idprojeto]->resumo, 'tags' => $projeto[$idprojeto]->tags, 'statusresumo' => $projeto[$idprojeto]->statusresumo, 'obsorientador' => $projeto[$idprojeto]->obsorientador));

if ($avaliacao->is_cancelled()) {
    redirect(VIEW_URL_LINK);
} else if ($feedback = $avaliacao->get_data()) {
    $alunos = $alunocontroller->getAlunosProjeto($idprojeto);
    $chat = new SendMessage();
    $chat->send($projeto[$idprojeto]->codprojeto, $projeto[$idprojeto]->titulo, $alunos, $feedback->statusresumo, $feedback->obsorientador);
    $professorcontroller = new ProfessorController();
    $professorcontroller->saveAvaliacaoOrientador($feedback, $idprojeto, $USER->username);
    redirect(VIEW_URL_LINK);
} else {
    $constantes = new Constantes();
    echo $OUTPUT->heading(get_string('avaliar_resumo', 'sepex'), 3);
    echo $OUTPUT->heading($projeto[$idprojeto]->codprojeto . ' - ' . $projeto[$idprojeto]->titulo, 4);
    
    $header.= '<b>' . get_string('alunos_projeto', 'sepex') . '</b>' . ': ' . implode(", ", $alunocontroller->getNameAlunos($idprojeto)) . '</br>';
    $header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $constantes->detailCursos($projeto[$idprojeto]->idcurso) . ' - ';
    $header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$idprojeto]->turno . '</br>';
    $header.= '<b>' . strtoupper(get_string('categoria', 'sepex')) . '</b>' . ': ' . $constantes->detailCategorias($projeto[$idprojeto]->idcategoria);
    echo $header;
    
    $avaliacao->display();
}

echo $OUTPUT->footer();
