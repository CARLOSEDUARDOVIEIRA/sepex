<?php

/**
 * TELA APRESENTADA AO PROFESSOR AVALIADOR PARA AVALIAÇÃO DO PROJETO 
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira  <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require ('../controllers/AlunoController.class.php');
require ('../classes/avaliacoes/Avaliacoes.class.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = optional_param('idprojeto', 0, PARAM_INT);
$idcategoria = optional_param('idcategoria', 0, PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$update = optional_param('update', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);

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

$PAGE->set_url('/mod/sepex/views/avaliador.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string('AVALIAR APRESENTAÇÃO DO PROJETO'), 2);
echo $OUTPUT->box(format_string(''), 2);

define('VIEW_URL_LINK', "../view.php?id=" . $id);

if (isset($idcategoria)) {
    $url = "avaliador.php?id={$id}&idprojeto={$idprojeto}&idcategoria={$idcategoria}";
    switch ($idcategoria) {
        case 1: //egressos
        case 3: //iniciacao
        case 5: //extensao
        case 8: //temaslivres
            require('../classes/avaliacoes/OutrasCategorias.class.php');
            $avaliacao = new OutrasCategorias($url, array('idprojeto' => $idprojeto));
            break;
        case 2: //estagios
            require ('../classes/avaliacoes/Estagio.class.php');
            $avaliacao = new Estagio($url, array('idprojeto' => $idprojeto));
            break;
        case 4: //inovacao
            require('../classes/avaliacoes/Inovacao.class.php');
            $avaliacao = new Inovacao($url, array('idprojeto' => $idprojeto));
            break;
        case 6: //integrador
            require('../classes/avaliacoes/Integrador.class.php');
            $avaliacao = new Integrador($url, array('idprojeto' => $idprojeto));
            break;
        case 7: //rsocial            
        case 9: //tcc
            require ('../classes/avaliacoes/TCC.class.php');
            $avaliacao = new TCC($url, array('idprojeto' => $idprojeto));
            break;
        case 10: //videos
            require('../classes/avaliacoes/Video.class.php');
            $avaliacao = new Video($url, array('idprojeto' => $idprojeto));
            break;
        case 11: //fotografia
            require('../classes/avaliacoes/Fotografia.class.php');
            $avaliacao = new Fotografia($url, array('idprojeto' => $idprojeto));
            break;
    }
    $avaliacao->display();


    if ($avaliacao->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($notas = $avaliacao->get_data()) {
        require '../controllers/AvaliacaoController.class.php';
        $avaliacaocontroller = new AvaliacaoController();
        $registrodeavaliacao = $avaliacaocontroller->getAvaliacao($idprojeto);
        $notas->totalresumo = $notas->resumo1 + $notas->resumo2 + $notas->resumo3 + $notas->resumo4 + $notas->resumo5;
        if ($registrodeavaliacao) {
            echo 'ULALALA';
        } else {
            echo '<pre>';
            print_r($avaliacaocontroller->save($idprojeto, $notas));
            echo '</pre>';
        }
        //guardar_presenca_aluno($data, $id_projeto);
        //redirect(VIEW_URL_LINK);
    }
}
//--Constantes
//    $egresos = get_string('egressos', 'sepex');
//    $estagios = get_string('estagios', 'sepex');
//    $iniciacao = get_string('iniciacao', 'sepex');
//    $inovacao = get_string('inovacao', 'sepex');
//    $extensao = get_string('extensao', 'sepex');
//    $integrador = get_string('integrador', 'sepex');        
//    $temaslivres = get_string('temaslivres', 'sepex');        
//    $video = get_string('video', 'sepex');
//    $fotografia = get_string('fotografia', 'sepex');
//    $responsabilidade = get_string('responsabilidadesocial', 'sepex');
//    $tcc = get_string('tcc', 'sepex');
//    if (!empty($id_projeto)) {    
//        $projeto = listar_projeto_por_id($id_projeto);        
//        $tipo = 'orientador';
//        $orientadores = listar_nome_professores($id_projeto, $tipo, $cm->course);
//        $categoria = retorna_categoria($projeto[$id_projeto]->cod_categoria);
//        $avaliadores = listar_nome_professores($id_projeto, 'avaliador', $cm->course);
//        $apresentacao = obter_dados_apresentacao($projeto[$id_projeto]->id_projeto);
//    }
//    //View header of page
//    $header  = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));                                                     
//    $header .= html_writer::start_tag('h5', array('class'=>'page-header'));
//    $header.= $projeto[$id_projeto]->cod_projeto.' - '.$projeto[$id_projeto]->titulo;
//    $header .= html_writer::end_tag('h5');
//    $header.= '<b>'.get_string('curso', 'sepex').'</b>'.': '.$projeto[$id_projeto]->curso_cod_curso.'</br>';
//    $header.= '<b>'.get_string('turno', 'sepex').'</b>'.': '.$projeto[$id_projeto]->turno.'</br>';
//    $header.= '<b>'.get_string('orientadores', 'sepex').'</b>'.': '.$orientadores.'</br>';
//    $header.= '<b>'.strtoupper(get_string('categoria', 'sepex')).'</b>'.': '.$categoria[$projeto[$id_projeto]->cod_categoria]->nome_categoria;
//    $header .= html_writer::end_tag('div');
//    echo $header;
//    
//    echo '<p>'.'<b>'.strtoupper(get_string('avaliadores', 'sepex')).'</b>'.': '.$avaliadores.'</p>';
//    echo '<p>'.'<b>'.get_string('local', 'sepex').'</b>'.':  '.$apresentacao[$projeto[$id_projeto]->id_projeto]->nome_local_apresentacao.'</p>';
//    echo '<p>'.'<b>'.get_string('apresentacao', 'sepex').'</b>'.':  '.date("d/m/Y H:i:s", $apresentacao[$projeto[$id_projeto]->id_projeto]->data_apresentacao).'</p></br>';                        
//    
//    if(isset($projeto[$id_projeto]->resumo)){
//        $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
//        $resumo .= html_writer::start_tag('p').$projeto[$id_projeto]->resumo.html_writer::end_tag('p');
//        $resumo .= html_writer::end_tag('div');
//        echo $resumo;
//        echo '<p></br>'.'<b>'.get_string('palavra_chave', 'sepex').'</b>'.':  '.$projeto[$id_projeto]->tags.'</p>';
//    }
//    
//    $dados_avaliacao = listar_dados_avaliacao_avaliador($id_projeto, $USER->username);
//     
//    if($projeto[$id_projeto]->cod_categoria == $integrador){                    
//        $mform = new Integrador("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,
//                                                                                       'resumo1' => $dados_avaliacao[$id_projeto]->resumo1,
//                                                                                       'resumo2' => $dados_avaliacao[$id_projeto]->resumo2,
//                                                                                       'resumo3' => $dados_avaliacao[$id_projeto]->resumo3,
//                                                                                       'resumo4' => $dados_avaliacao[$id_projeto]->resumo4,
//                                                                                       'resumo5' => $dados_avaliacao[$id_projeto]->resumo5,
//                                                                                       'total_resumo' => $dados_avaliacao[$id_projeto]->total_resumo,
//                                                                                       'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                       'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                       'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                       'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,
//                                                                                       'apresentacao5' => $dados_avaliacao[$id_projeto]->avaliacao5,
//                                                                                       'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                     ));        
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $estagios){                    
//        $mform = new Estagio("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,
//                                                                                    'resumo1' => $dados_avaliacao[$id_projeto]->resumo1,
//                                                                                    'resumo2' => $dados_avaliacao[$id_projeto]->resumo2,
//                                                                                    'resumo3' => $dados_avaliacao[$id_projeto]->resumo3,
//                                                                                    'resumo4' => $dados_avaliacao[$id_projeto]->resumo4,
//                                                                                    'resumo5' => $dados_avaliacao[$id_projeto]->resumo5,
//                                                                                    'total_resumo' => $dados_avaliacao[$id_projeto]->total_resumo,
//                                                                                    'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                    'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                    'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                    'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,
//                                                                                    'apresentacao5' => $dados_avaliacao[$id_projeto]->avaliacao5,
//                                                                                    'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                 )); 
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $fotografia){                    
//        $mform = new Fotografia("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,                                                                                                                                                                       
//                                                                                    'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                    'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                    'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                    'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,
//                                                                                    'apresentacao5' => $dados_avaliacao[$id_projeto]->avaliacao5,
//                                                                                    'apresentacao6' => $dados_avaliacao[$id_projeto]->avaliacao6,
//                                                                                    'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                 ));         
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $inovacao){                    
//        $mform = new Inovacao("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,
//                                                                                    'resumo1' => $dados_avaliacao[$id_projeto]->resumo1,
//                                                                                    'resumo2' => $dados_avaliacao[$id_projeto]->resumo2,
//                                                                                    'resumo3' => $dados_avaliacao[$id_projeto]->resumo3,
//                                                                                    'resumo4' => $dados_avaliacao[$id_projeto]->resumo4,
//                                                                                    'resumo5' => $dados_avaliacao[$id_projeto]->resumo5,
//                                                                                    'total_resumo' => $dados_avaliacao[$id_projeto]->total_resumo,
//                                                                                    'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                    'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                    'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                    'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,
//                                                                                    'apresentacao5' => $dados_avaliacao[$id_projeto]->avaliacao5,
//                                                                                    'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                 ));        
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $video){                    
//        $mform = new Video("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,                                                                                                                                                                       
//                                                                                    'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                    'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                    'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                    'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,
//                                                                                    'apresentacao5' => $dados_avaliacao[$id_projeto]->avaliacao5,                                                                                    
//                                                                                    'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                 ));         
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $egresos || $projeto[$id_projeto]->cod_categoria == $iniciacao || $projeto[$id_projeto]->cod_categoria == $extensao || $projeto[$id_projeto]->cod_categoria == $temaslivres){
//        $mform = new OutrasCategorias("acao_avaliacao.php?id={$id}&data={$id_projeto}",array('id_projeto' => $id_projeto,
//                                                                                    'resumo1' => $dados_avaliacao[$id_projeto]->resumo1,
//                                                                                    'resumo2' => $dados_avaliacao[$id_projeto]->resumo2,
//                                                                                    'resumo3' => $dados_avaliacao[$id_projeto]->resumo3,
//                                                                                    'resumo4' => $dados_avaliacao[$id_projeto]->resumo4,
//                                                                                    'resumo5' => $dados_avaliacao[$id_projeto]->resumo5,
//                                                                                    'total_resumo' => $dados_avaliacao[$id_projeto]->total_resumo,
//                                                                                    'apresentacao1' => $dados_avaliacao[$id_projeto]->avaliacao1,
//                                                                                    'apresentacao2' => $dados_avaliacao[$id_projeto]->avaliacao2,
//                                                                                    'apresentacao3' => $dados_avaliacao[$id_projeto]->avaliacao3,
//                                                                                    'apresentacao4' => $dados_avaliacao[$id_projeto]->avaliacao4,                                                                                    
//                                                                                    'total_apresentacao'=> $dados_avaliacao[$id_projeto]->total_avaliacao                                                                                                
//                                                                                 )); 
//    }
//    elseif($projeto[$id_projeto]->cod_categoria == $tcc || $projeto[$id_projeto]->cod_categoria == $responsabilidade){
//        $mform = new TCC("acao_avaliacao.php?id={$id}&data={$id_projeto}",array(    'resumo1' => $dados_avaliacao[$id_projeto]->resumo1,
//                                                                                    'resumo2' => $dados_avaliacao[$id_projeto]->resumo2,
//                                                                                    'resumo3' => $dados_avaliacao[$id_projeto]->resumo3,
//                                                                                    'resumo4' => $dados_avaliacao[$id_projeto]->resumo4,
//                                                                                    'resumo5' => $dados_avaliacao[$id_projeto]->resumo5,
//                                                                                    'total_resumo' => $dados_avaliacao[$id_projeto]->total_resumo,                                                                                    
//                                                                                 )); 
//    }
//Fim da página
echo $OUTPUT->footer();
