<?php

/* GERAR RESUMOS FORMATADOS PADRAO REVISTA */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require('../classes/GerarResumoRevista.class.php');
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
$context_course = context_course::instance($course->id);

$PAGE->set_url('/mod/sepex/views/aluno.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));
define('VIEW_URL_LINK', "../view.php?id=" . $id);

echo $OUTPUT->header();

echo $OUTPUT->heading(strtoupper(get_string('resumo_revista', 'sepex')), 2);

$categoria = new GerarResumoRevista("GerarResumoRevista.php?id={$id}");

$categoria->display();

if ($categoria->is_cancelled()) {
    redirect(VIEW_URL_LINK);
} else if ($categoria->get_data()) {
    echo 'ULALALA';
    print_r($categoria->get_data());
   // echo("<meta http-equiv='refresh' content='0'>");
}

echo $OUTPUT->footer();
