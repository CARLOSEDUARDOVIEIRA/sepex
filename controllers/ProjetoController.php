<?php

require_once '../models/ProjetoModel.php';
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');

//$id = required_param('id', PARAM_INT);
//$s = optional_param('s', 0, PARAM_INT);
//$acao = optional_param('acao', 0, PARAM_INT);
//
//if ($id) {
//    $cm = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
//    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
//    $sepex = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
//} else if ($s) {
//    $sepex = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
//    $course = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
//    $cm = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
//} else {
//    error('Você deve especificar um course_module ID ou um ID de instância');
//}
//
//require_login($course, true, $cm);
//if ($dados = $mform->get_data()):
$dados = new stdClass();
$dados->cod_categoria = optional_param('categoria', 0, PARAM_INT);
$dados->titulo = optional_param('titulo', null, PARAM_RAW);
$dados->resumo = optional_param('resumo', null, PARAM_RAW);
$dados->tags = optional_param('tags', null, PARAM_RAW);
$dados->periodo = optional_param('periodo', 0, PARAM_INT);
$dados->turno = optional_param('turno', null, PARAM_RAW);
$dados->cod_curso = optional_param('cod_curso', 0, PARAM_INT);
$dados->aloca_mesa = optional_param('mesa', 0, PARAM_INT);
$dados->matriculaAluno = "6914104289";
$dados->matriculaProfessor = "691410";

$retorno = CreateProjeto($dados);

function CreateProjeto($dados) {
    return new Projeto($dados);
}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
echo json_encode($retorno);
//endif;