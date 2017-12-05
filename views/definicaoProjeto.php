<?php

/* EXIBE A TELA PARA ATRIBUIR UM LOCAL DE APRESENTAÇÃO - PROFESSORES AVALIADORES - DIA - HORA - PARA UM PROJETO
 */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require ('../classes/FormularioDefinicaoProjeto.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../constantes/Constantes.class.php');
require ('../controllers/ProfessorController.class.php');
require ('../controllers/ApresentacaoController.class.php');
require ('../controllers/AlunoController.class.php');

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
$alunocontroller = new AlunoController();

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
echo '<hr>';

$alunos = $alunocontroller->getAlunosProjeto($idprojeto);

echo '<table class="forumheaderlist table table-striped">';
echo '<thead>';
echo '<tr>';
//echo '<th>' . strtoupper(get_string('aluno', 'sepex')) . '</th>';
echo '<th>' . get_string('apresentacao', 'sepex') . ' | ' . strtoupper(get_string('avaliadores', 'sepex')) . '</th>';
echo '</tr>';
echo '</thead>';
foreach (explode(';', $alunos) as $i => $matraluno) {
    $infoaluno = $alunocontroller->getLocalApresentacaoAluno($matraluno);
    echo '<tbody>';
    echo '<tr>';
    //echo '<td>' . array_column($infoaluno, 'name')[0] . '</td>';
    echo '<td>';
    foreach ($infoaluno as $info) {
	echo $info->name;
        echo "<a href='definicaoProjeto.php?id={$id}&idprojeto={$info->idprojeto}&area={$area}&turno={$turno}&idcategoria={$idcategoria}'";
        echo ' <br> ' . date("d/m/Y H:i:s", $info->dtapresentacao) . ' - ' . $info->nomelocalapresentacao;
        echo ' | ' . implode(',', $professorcontroller->getNameProfessores($info->idprojeto, 'Avaliador'));
        echo '</a>';
        echo '<br>';
    }
    echo '<td>';
    echo '</td>';
    echo '</td>';
    echo '</tr>';
    echo '</tbody>';
}
echo '</table>';


$apresentacao = $apresentacaocontroller->detailApresentacao($idprojeto);

if (!$apresentacao) {
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

if ($data = $definicao->get_data()) {
    $data->idprojeto = $idprojeto;
    if (isset($data->update)) {
        $apresentacaocontroller->update($data, $avaliadores);
    } else {
        $apresentacaocontroller->save($data);
    }
    redirect("definirApresentacao.php?id={$id}&area={$area}&turno={$turno}&idcategoria={$idcategoria}");
}

echo $OUTPUT->footer();
