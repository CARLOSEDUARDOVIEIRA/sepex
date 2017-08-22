<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

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

$lang = current_language();
require_login($course, true, $cm);
$context_course = context_course::instance($course->id);

$PAGE->set_url('/mod/sepex/views/gerente.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('organizacaosepex','sepex'), 2);
echo $OUTPUT->box(format_module_intro('sepex', $sepex, $cm->id), 'generalbox', 'intro');
echo '<hr>';

$criarlocal = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$criarlocal .= html_writer::start_tag('a', array('href' => './cadastroLocais.php?id=' . $id.'&add=1',));
$criarlocal .= html_writer::start_tag('img', array('src' => '../pix/cadloc.png'));
$criarlocal .= get_string('criar_local_apresentacao', 'sepex');
$criarlocal .= html_writer::end_tag('a');
$criarlocal .= html_writer::end_tag('div');
echo $criarlocal;
echo '<hr>';

$definirapresentacao = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$definirapresentacao .= html_writer::start_tag('a', array('href' => './definirApresentacao.php?id=' . $id,));
$definirapresentacao .= html_writer::start_tag('img', array('src' => '../pix/locapre.png'));
$definirapresentacao .= get_string('definir_local_apresentacao', 'sepex');
$definirapresentacao .= html_writer::end_tag('a');
$definirapresentacao .= html_writer::end_tag('div');
echo $definirapresentacao;
echo '<hr>';

$relprojetos = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$relprojetos .= html_writer::start_tag('a', array('href' => './relProjetos.php?id=' . $id,));
$relprojetos .= html_writer::start_tag('img', array('src' => '../pix/relcad.png'));
$relprojetos .= get_string('exibir_relatorios', 'sepex');
$relprojetos .= html_writer::end_tag('a');
$relprojetos .= html_writer::end_tag('div');
echo $relprojetos;
echo '<hr>';

$resumorevista = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:2%;'));
$resumorevista .= html_writer::start_tag('a', array('href' => './GerarResumoRevista.php?id=' . $id,));
$resumorevista .= html_writer::start_tag('img', array('src' => '../pix/doc.png'));
$resumorevista .= get_string('resumo_revista', 'sepex');
$resumorevista .= html_writer::end_tag('a');
$resumorevista .= html_writer::end_tag('div');
echo $resumorevista;
echo '<hr>';

echo $OUTPUT->footer();
