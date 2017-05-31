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
require_once ('../classes/formularios_avaliacoes/Estagio.class.php');
require_once ('../classes/formularios_avaliacoes/Fotografia.class.php');
require_once ('../classes/formularios_avaliacoes/Inovacao.class.php');
require_once ('../classes/formularios_avaliacoes/Integrador.class.php');
require_once ('../classes/formularios_avaliacoes/Video.class.php');
require_once ('../classes/formularios_avaliacoes/OutrasCategorias.class.php');
require_once ('../classes/formularios_avaliacoes/TCC.class.php');

global $DB, $CFG, $PAGE;
$id = required_param('id', PARAM_INT);
$s  = optional_param('s', 0, PARAM_INT);
$id_projeto = optional_param('data', 0, PARAM_INT);

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

//--Constantes
    $egresos = get_string('egressos', 'sepex');
    $estagios = get_string('estagios', 'sepex');
    $iniciacao = get_string('iniciacao', 'sepex');
    $inovacao = get_string('inovacao', 'sepex');
    $extensao = get_string('extensao', 'sepex');
    $integrador = get_string('integrador', 'sepex');        
    $temaslivres = get_string('temaslivres', 'sepex');        
    $video = get_string('video', 'sepex');
    $fotografia = get_string('fotografia', 'sepex');
    $responsabilidade = get_string('responsabilidadesocial', 'sepex');
    $tcc = get_string('tcc', 'sepex');


    if (!empty($id_projeto)) {    
        $projeto = listar_projeto_por_id($id_projeto);        
    }
    
    if($projeto[$id_projeto]->cod_categoria == $integrador){    
        $mform = new Integrador();    
    }elseif($projeto[$id_projeto]->cod_categoria == $estagios){                    
            $mform = new Estagio();        
    }
    elseif($projeto[$id_projeto]->cod_categoria == $fotografia){                    
            $mform = new Fotografia();        
        }
    elseif($projeto[$id_projeto]->cod_categoria == $inovacao){                    
            $mform = new Inovacao();        
        }
    elseif($projeto[$id_projeto]->cod_categoria == $video){                    
            $mform = new Video();        
    }
    elseif($projeto[$id_projeto]->cod_categoria == $egresos || $projeto[$id_projeto]->cod_categoria == $iniciacao || $projeto[$id_projeto]->cod_categoria == $extensao || $projeto[$id_projeto]->cod_categoria == $temaslivres){
        $mform = new OutrasCategorias();
    }
    elseif($projeto[$id_projeto]->cod_categoria == $tcc || $projeto[$id_projeto]->cod_categoria == $responsabilidade){
        $mform = new TCC();
    }

    if($mform->is_cancelled()):
        redirect("../view.php?id={$id}&data={$id_projeto}");
    elseif ($data = $mform->get_data()):
        guardar_avaliacao_avaliador($data,$id_projeto, $USER->username);
        guardar_presenca_aluno($data,$id_projeto);
        //header("Location: ../view.php?id={$id}&data={$id_projeto}");        
//        echo '<pre>';
//            print_r($data);
//        echo '<pre>';
    endif;
    
  
  
    
