<?php

/**
* AÇÃO SOBRE O FORMULARIO DE AVALIAÇÃO DO PROJETO REALIZADA PELO PROFESSOR ORIENTADOR
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira  <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');

global $DB, $CFG, $PAGE;
$id = required_param('id', PARAM_INT);
$s  = optional_param('s', 0, PARAM_INT);
if ($id) {
    $cm         = get_coursemodule_from_id('sepex', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sepex  = $DB->get_record('sepex', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($s) {
    $sepex  = $DB->get_record('sepex', array('id' => $s), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $sepex->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('sepex', $sepex->id, $course->id, false, MUST_EXIST);
} else {
    error('Você deve especificar um course_module ID ou um ID de instância');
}

$lang = current_language();
require_login($course, true, $cm);
$context_course = context_course::instance($course -> id);

//    if(isset($_GET['acao'])){
    //        if($mform->is_cancelled()):
    //            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
    //        elseif ($data = $mform->get_data()):
    //            alterar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
    //            guardar_professor($id_projeto,$data->avaliador,$tipo2);
    //            guardar_professor($id_projeto,$data->avaliador2,$tipo2);
    //            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
    //        else:
    //            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
    //        endif;        
    //    }else{
    //        if($mform->is_cancelled()):
    //            redirect("view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");        
    //        elseif ($data = $mform->get_data()):           
    //            guardar_professor($id_projeto,$data->avaliador,$tipo2);
    //            guardar_professor($id_projeto,$data->avaliador2,$tipo2);
    //            guardar_definicao_projeto($id_projeto, $data->localapresentacao, $data->data_apresentacao);
    //            header("Location: view.php?id={$id}&are={$area}&tur={$turno}&cat={$categoria}");
    //        else:
    //            header_definicao_projeto($sepex, $cm, $projeto, $orientadores, $id_projeto, $mform);
    //        endif;
    //    } 