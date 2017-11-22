<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require('../classes/Formulario.class.php');
require('../controllers/ProjetoController.class.php');
require('../controllers/AlunoController.class.php');
require('../controllers/ProfessorController.class.php');
require ('../locallib.php');

global $DB, $CFG, $PAGE;

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$update = optional_param('update', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
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

$lang = current_language();
require_login($course, true, $cm);
$modcontext = context_module::instance($cm->id);

$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

define('VIEW_URL_LINK', "../view.php?id=" . $id);
echo $OUTPUT->header();

$controller = new ProjetoController();
$alunocontroller = new AlunoController();
$professorcontroller = new ProfessorController();

if (!empty($add)) {

    $formulario = new Formulario("cadastroProjeto.php?id={$id}&add=1", array('course' => $cm->course));

    if ($formulario->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($formulario->get_data()) {
        $controller->save($formulario->get_data());
        enviar_email($USER, $formulario->get_data());
        redirect(VIEW_URL_LINK);
    } else {
        echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
        $formulario->display();
    }
} else if (!empty($update)) {
    $projeto = $controller->detail($idprojeto);
    $alunos = $alunocontroller->getAlunosProjeto($idprojeto);
    $professores = $professorcontroller->getProfessorProjeto($idprojeto, 'Orientador');

    //Instanciação de um novo formulario passando como parametro: (destino_formulario, array(informe aqui os campos e os valores dos campos)                
    $formulario = new Formulario("cadastroProjeto.php?id={$id}&update=1&idprojeto={$idprojeto}", array('modcontext' => $modcontext,
        'course' => $cm->course,
        'idcurso' => $projeto[$idprojeto]->idcurso,
        'idperiodo' => $projeto[$idprojeto]->idperiodo,
        'turno' => $projeto[$idprojeto]->turno,
        'idcategoria' => $projeto[$idprojeto]->idcategoria,
        'titulo' => $projeto[$idprojeto]->titulo,
        'matraluno' => $alunos,
        'resumo' => $projeto[$idprojeto]->resumo,
        'tags' => $projeto[$idprojeto]->tags,
        'alocamesa' => $projeto[$idprojeto]->alocamesa,
        'matrprofessor' => $professores[0]));

    if ($formulario->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($projeto = $formulario->get_data()) {
        $projeto->idprojeto = $idprojeto;
        $controller->update($projeto);
        redirect(VIEW_URL_LINK);
    } else {
        echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
        $formulario->display();
    }
} else if (!empty($delete)) {
    if ($delete == 1) {
        echo $OUTPUT->confirm(get_string("delete", "sepex"), "cadastroProjeto.php?id={$id}&delete=2&idprojeto={$idprojeto}", $CFG->wwwroot . '/mod/sepex/view.php?id=' . $id);
    } elseif ($delete == 2) {
        $controller->delete($idprojeto);
        redirect(VIEW_URL_LINK);
    }
}

echo $OUTPUT->footer();
