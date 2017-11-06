<?php

/**
 * TELA APRESENTADA AOS ALUNOS AO FINAL DAS INSCRIÇÕES
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira  <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require '../controllers/ApresentacaoController.class.php';
require ('../controllers/ProjetoController.class.php');
require ('../controllers/AlunoController.class.php');
require ('../constantes/Constantes.class.php');
require ('../controllers/ProfessorController.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = required_param('idprojeto', PARAM_INT);

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
$context_course = context_course::instance($course->id);
$PAGE->set_url('/mod/sepex/views/projetoAluno.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('PROJETO ALUNO'), 2);
echo $OUTPUT->box(format_string(''), 2);

$voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id,));
$voltar .= html_writer::start_tag('img', array('src' => '../pix/left-arrow.png'));
$voltar .= ' ' . get_string('voltar_menu', 'sepex');
$voltar .= html_writer::end_tag('a');
echo $voltar;
echo '<hr>';

$constantes = new Constantes();
$projetocontroller = new ProjetoController();
$professorcontroller = new ProfessorController();
$alunocontroller = new AlunoController();
$apresentacaocontroller = new ApresentacaoController();

$projeto = $projetocontroller->detail($idprojeto);

$header = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));
$header .= html_writer::start_tag('h5', array('class' => 'page-header'));
$header.= $projeto[$idprojeto]->codprojeto . ' - ' . $projeto[$idprojeto]->titulo;
$header .= html_writer::end_tag('h5');
$header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $constantes->detailCursos($projeto[$idprojeto]->idcurso) . '</br>';
$header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$idprojeto]->turno . '</br>';
$header.= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . implode(',', $professorcontroller->getNameProfessores($idprojeto, 'Orientador')) . '</br>';
$header.= '<b>' . strtoupper(get_string('categoria', 'sepex')) . '</b>' . ': ' . $constantes->detailCategorias($projeto[$idprojeto]->idcategoria) . '</br>';
$header.= '<b>' . get_string('alunos_projeto', 'sepex') . '</b>' . ': ' . implode(", ", $alunocontroller->getNameAlunos($idprojeto));
$header .= html_writer::end_tag('div');
echo $header;


if (isset($projeto)) {
    $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
    $resumo .= html_writer::start_tag('p') . $projeto[$idprojeto]->resumo . html_writer::end_tag('p');
    $resumo .= html_writer::end_tag('div');
    echo $resumo;

    echo '<p></br>' . '<b>' . get_string('palavra_chave', 'sepex') . '</b>' . ':  ' . $projeto[$idprojeto]->tags . '</p>';
}

if (isset($projeto[$idprojeto]->statusresumo)) {

    $status = $situacao[$idprojeto]->statusresumo == 0 ? "Reprovado" : "Aprovado";

    echo '<p>' . '<b>' . get_string('status_resumo', 'sepex') . '</b>' . ':  ' . $status . '</p>';
    echo '<p>' . '<b>' . get_string('obs_orientador', 'sepex') . '</b>' . ':  ' . $projeto[$idprojeto]->obsorientador . '</p>';
} else {
    echo '<p>' . '<b>' . get_string('status_resumo', 'sepex') . '</b>' . ':  ' . get_string('em_avaliacao', 'sepex') . '</p>';
    echo '<p>' . '<b>' . get_string('obs_orientador', 'sepex') . '</b>' . ':  ' . get_string('em_avaliacao', 'sepex') . '</p>';
}

echo '<p>' . '</br></br>' . get_string('local_apresentacao', 'sepex') . '</p></br>';
if ($projeto->alocamesa) {
    echo '<p>' . '<b>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</b>' . ':  ' . get_string('projeto_solicita_mesa', 'sepex') . '</p>';
} else {
    echo '<p>' . '<b>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</b>' . ':  ' . get_string('projeto_nao_solicita_mesa', 'sepex') . '</p>';
}

$apresentacao = $apresentacaocontroller->detailApresentacao($idprojeto);

if (isset($apresentacao)) {
    echo '<p>' . '<b>' . strtoupper(get_string('avaliadores', 'sepex')) . '</b>' . ': ' . implode(',', $professorcontroller->getNameProfessores($idprojeto, 'Avaliador')) . '</p>';
    echo '<p>' . '<b>' . get_string('local', 'sepex') . '</b>' . ':  ' . $apresentacao->nomelocalapresentacao . '</p>';
    echo '<p>' . '<b>' . get_string('apresentacao', 'sepex') . '</b>' . ':  ' . date("d/m/Y H:i:s", $apresentacao->dtapresentacao) . '</p>';
} else {
    echo '<p>' . '<b>' . strtoupper(get_string('avaliadores', 'sepex')) . '</b>' . ': ' . get_string('aguardando_definicao', 'sepex') . '</p>';
    echo '<p>' . '<b>' . get_string('local', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
    echo '<p>' . '<b>' . get_string('apresentacao', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
}

$alunos = $alunocontroller->getNameAlunos($idprojeto);
$presentes = array();
$ausentes = array();
foreach ($alunos as $matraluno => $value) {
    if ($alunocontroller->getPresencaAluno($idprojeto, $matraluno)[$idprojeto]->presenca) {
        array_push($presentes, $value);
    } else {
        array_push($ausentes, $value);
    }
}

$notas = $alunocontroller->getNotaFinalProjetoAluno($idprojeto);
$notafinal = 0;
foreach ($notas as $n) {
    $notafinal += $n->notafinal;
}

if ($notafinal) {
    if ($presentes) {
        echo '<p>' . '<b>' . get_string('alunos_prese_apres', 'sepex') . '</b>' . ':  ' . implode(' , ', $presentes) . '</p>';
    }
    if ($ausentes) {
        echo '<p>' . '<b>' . get_string('alunos_falta_apres', 'sepex') . '</b>' . ':  ' . implode(' , ', $ausentes) . '</p>';
    }
}

if (has_capability('mod/sepex:openformulario', $context_course)) {
    echo '<p>' . '<b>' . get_string('nota_final', 'sepex') . '</b>' . ':  ' . $notafinal . '</p>';
}

//Fim da página
echo $OUTPUT->footer();
