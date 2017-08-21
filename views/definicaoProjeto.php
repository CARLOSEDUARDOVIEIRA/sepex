<?php

/* EXIBE A TELA PARA ATRIBUIR UM LOCAL DE APRESENTAÇÃO - PROFESSORES AVALIADORES - DIA - HORA - PARA UM PROJETO
 */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require ('../classes/FormularioDefinicaoProjeto.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../constantes/Constantes.class.php');
require ('../controllers/ProfessorController.class.php');
require ('../controllers/ApresentacaoController.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = required_param('idprojeto', PARAM_INT);
$area = optional_param('area', 0, PARAM_INT);
$turno = optional_param('turno', null, PARAM_RAW);
$idcategoria = optional_param('idcategoria', 0, PARAM_INT);

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

$PAGE->set_url('/mod/sepex/definicao_projeto.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

$constantes = new Constantes();
$projetocontroller = new ProjetoController();
$professorcontroller = new ProfessorController();
$apresentacaocontroller = new ApresentacaoController();

$projeto = $projetocontroller->detail($idprojeto);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('definir_apresentacao', 'sepex'), 2);
echo '<hr>';

echo $OUTPUT->heading($projeto[$idprojeto]->codprojeto . ' - <b>' . $projeto[$idprojeto]->titulo . '</b>', 4);

$header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $constantes->detailCursos($projeto[$idprojeto]->idcurso) . ' - ';
$header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$idprojeto]->turno . '</br>';
$header.= '<b>' . get_string('periodo', 'sepex') . '</b>' . ': ' . $projeto[$idprojeto]->idperiodo . ' Periodo </br>';
$header.= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . implode(',', $professorcontroller->getNameProfessores($idprojeto, 'Orientador'));
echo $header;



$apresentacao = $apresentacaocontroller->detailApresentacao($idprojeto);

if (empty($apresentacao)) {
    $definicao = new FormularioDefinicaoProjeto("definicaoProjeto.php?id={$id}&idprojeto={$idprojeto}&area={$area}&turno={$turno}&idcategoria={$idcategoria}", array('course' => $cm->course));
} else {
    $avaliadores = $professorcontroller->getProfessorProjeto($idprojeto, 'Avaliador');
    $definicao = new FormularioDefinicaoProjeto("definicaoProjeto.php?id={$id}&idprojeto={$idprojeto}&area={$area}&turno={$turno}&idcategoria={$idcategoria}", array(
        'dtapresentacao' => $apresentacao->dtapresentacao,
        'idlocalapresentacao' => $apresentacao->idlocalapresentacao,
        'avaliador' => $avaliadores[0], 'avaliador2' => $avaliadores[1], 'course' => $cm->course)
    );
}

$definicao->display();

if ($definicao->is_cancelled()) {
    redirect("definirApresentacao.php?id={$id}&area={$area}&turno={$turno}&idcategoria={$idcategoria}");
}

if (!empty($data = $definicao->get_data())) {
    $data->idprojeto = $idprojeto;
    if (isset($data->update)) {
        $apresentacaocontroller->update($data, $avaliadores);
    } else {
        $apresentacaocontroller->save($data);
    }
    redirect("definirApresentacao.php?id={$id}&area={$area}&turno={$turno}&idcategoria={$idcategoria}");
}

echo $OUTPUT->footer();
