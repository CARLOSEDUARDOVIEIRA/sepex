<?php

/**
 * TELA APRESENTADA AOS ALUNOS AO FINAL DAS INSCRIÇÕES
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira  <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
//require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
//require_once('../locallib.php');
//
$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = optional_param('idprojeto', 0, PARAM_INT);
$notafinal = optional_param('n', 0, PARAM_FLOAT);

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

//$PAGE->set_url('/mod/sepex/avaliacao_avaliador.php', array('id' => $cm->id));
//$PAGE->set_title(format_string($sepex->name));
//$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('PROJETO ALUNO'), 2);
echo $OUTPUT->box(format_string(''), 2);
//
//if (!empty($id_projeto)) {
//    $projeto = listar_projeto_por_id($id_projeto);
//    $tipo = 'orientador';
//    $orientadores = listar_nome_professores($id_projeto, $tipo, $cm->course);
//    $categoria = retorna_categoria($projeto[$id_projeto]->cod_categoria);
//    $avaliadores = listar_nome_professores($id_projeto, 'avaliador', $cm->course);
//    $apresentacao = obter_dados_apresentacao($projeto[$id_projeto]->id_projeto);
//    $alunos = listar_nome_alunos($id_projeto);
//    $integrantes = array();
//    foreach ($alunos as $aluno) {
//        array_push($integrantes, $aluno->name);
//    }
//    $lista_alunos = implode(", ", $integrantes);
//    $situacao = listar_situacao_resumo($id_projeto);
//}
//
//// View header of page
//$link_voltar = html_writer::start_tag('a', array('href' => '../view.php?id=' . $id));
//$link_voltar .= get_string('voltar_menu', 'sepex');
//$link_voltar .= html_writer::end_tag('a');
//echo $link_voltar;
//
//$header = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));
//$header .= html_writer::start_tag('h5', array('class' => 'page-header'));
//$header.= $projeto[$id_projeto]->cod_projeto . ' - ' . $projeto[$id_projeto]->titulo;
//$header .= html_writer::end_tag('h5');
//$header.= '<b>' . get_string('alunos_projeto', 'sepex') . '</b>' . ': ' . $lista_alunos . '</br>';
//$header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->curso_cod_curso . '</br>';
//$header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$id_projeto]->turno . '</br>';
//$header.= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . $orientadores . '</br>';
//$header.= '<b>' . strtoupper(get_string('categoria', 'sepex')) . '</b>' . ': ' . $categoria[$projeto[$id_projeto]->cod_categoria]->nome_categoria;
//$header .= html_writer::end_tag('div');
//echo $header;
//if (isset($projeto[$id_projeto]->resumo)) {
//    $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
//    $resumo .= html_writer::start_tag('p') . $projeto[$id_projeto]->resumo . html_writer::end_tag('p');
//    $resumo .= html_writer::end_tag('div');
//    echo $resumo;
//
//    echo '<p></br>' . '<b>' . get_string('palavra_chave', 'sepex') . '</b>' . ':  ' . $projeto[$id_projeto]->tags . '</p>';
//}
//
//if ($situacao[$id_projeto]->status_resumo != null) {
//    if ($situacao[$id_projeto]->status_resumo == 0):
//        $status = 'Reprovado';
//    elseif ($situacao[$id_projeto]->status_resumo == 1):
//        $status = 'Aprovado';
//    endif;
//    echo '<p>' . '<b>' . get_string('status_resumo', 'sepex') . '</b>' . ':  ' . $status . '</p>';
//    echo '<p>' . '<b>' . get_string('obs_orientador', 'sepex') . '</b>' . ':  ' . $situacao[$id_projeto]->obs_orientador . '</p>';
//}else {
//    echo '<p>' . '<b>' . get_string('status_resumo', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
//    echo '<p>' . '<b>' . get_string('obs_orientador', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
//}
//
//echo '<p>' . '</br></br>' . get_string('local_apresentacao', 'sepex') . '</p></br>';
//
//if ($projeto->aloca_mesa) {
//    echo '<p>' . '<b>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</b>' . ':  ' . get_string('projeto_solicita_mesa', 'sepex') . '</p>';
//} else {
//    echo '<p>' . '<b>' . strtoupper(get_string('solicita_mesa', 'sepex')) . '</b>' . ':  ' . get_string('projeto_nao_solicita_mesa', 'sepex') . '</p>';
//}
//
//
//if (isset($apresentacao[$projeto[$id_projeto]->id_projeto]->nome_local_apresentacao)) {
//    echo '<p>' . '<b>' . strtoupper(get_string('avaliadores', 'sepex')) . '</b>' . ': ' . $avaliadores . '</p>';
//    echo '<p>' . '<b>' . get_string('local', 'sepex') . '</b>' . ':  ' . $apresentacao[$projeto[$id_projeto]->id_projeto]->nome_local_apresentacao . '</p>';
//    echo '<p>' . '<b>' . get_string('apresentacao', 'sepex') . '</b>' . ':  ' . date("d/m/Y H:i:s", $apresentacao[$projeto[$id_projeto]->id_projeto]->data_apresentacao) . '</p>';
//} else {
//    echo '<p>' . '<b>' . strtoupper(get_string('avaliadores', 'sepex')) . '</b>' . ': ' . get_string('aguardando_definicao', 'sepex') . '</p>';
//    echo '<p>' . '<b>' . get_string('local', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
//    echo '<p>' . '<b>' . get_string('apresentacao', 'sepex') . '</b>' . ':  ' . get_string('aguardando_definicao', 'sepex') . '</p>';
//}
//
//$alunos = listar_nome_alunos($id_projeto);
//$presentes = array();
//$faltosos = array();
//foreach ($alunos as $aluno) {
//    $presenca_aluno = listar_presenca_aluno_matricula($id_projeto, $aluno->username);
//    if ($presenca_aluno[$id_projeto]->presenca) {
//        array_push($presentes, $aluno->name);        
//    } else {
//        array_push($faltosos, $aluno->name);
//    }
//}
//
//if ($presentes){
//    echo '<p>' . '<b>' . get_string('alunos_prese_apres', 'sepex') . '</b>' . ':  ' . implode(' , ', $presentes) . '</p>';
//} 
//if($faltosos) {
//    echo '<p>' . '<b>' . get_string('alunos_falta_apres', 'sepex') . '</b>' . ':  ' . implode(' , ', $faltosos) . '</p>';
//}
//
//
//if (has_capability('mod/sepex:openformulario', $context_course)) {
//    echo '<p>' . '<b>' . get_string('nota_final', 'sepex') . '</b>' . ':  ' . $nota_final . '</p>';
//}
//
////Fim da página
echo $OUTPUT->footer();

////require "config.php";
//require "$CFG->libdir/tablelib.php";
//$context = context_system::instance();
//$PAGE->set_context($context);
//$PAGE->set_url('/test.php');
//
//$download = optional_param('download', '', PARAM_ALPHA);
//
//$table = new table_sql('uniqueid');
//$table->is_downloading($download, 'test', 'testing123');
//
//if (!$table->is_downloading()) {
//    // Only print headers if not asked to download data
//    // Print the page header
//    $PAGE->set_title('Testing');
//    $PAGE->set_heading('Testing table class');
//    $PAGE->navbar->add('Testing table class', new moodle_url('/test.php'));
//    echo $OUTPUT->header();
//}
//
//// Work out the sql for the table.
//$table->set_sql('*', "{user}", '1');
//
//$table->define_baseurl("$CFG->wwwroot/test.php");
//
//$table->out(40, true);
//
//if (!$table->is_downloading()) {
//    $OUTPUT->footer();
//}