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
require ('../constantes/Constantes.class.php');
require ('../controllers/ProfessorController.class.php');
require '../controllers/AvaliacaoController.class.php';
require '../controllers/ApresentacaoController.class.php';
require ('../controllers/ProjetoController.class.php');


$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$idprojeto = required_param('idprojeto', PARAM_INT);
$idcategoria = required_param('idcategoria', PARAM_INT);

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

$constantes = new Constantes();
$professorcontroller = new ProfessorController();

// Se uma categoria nao for informada de nada serve essa pagina

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

    $projetocontroller = new ProjetoController();
    $projeto = $projetocontroller->detail($idprojeto);
    $apresentacaocontroller = new ApresentacaoController();

    $header = html_writer::start_tag('div', array('style' => 'margin-bottom:5%;'));
    $header .= html_writer::start_tag('h5', array('class' => 'page-header'));
    $header.= $projeto[$idprojeto]->codprojeto . ' - ' . $projeto[$idprojeto]->titulo;
    $header .= html_writer::end_tag('h5');
    $header.= '<b>' . get_string('curso', 'sepex') . '</b>' . ': ' . $constantes->detailCursos($projeto[$idprojeto]->idcurso) . '</br>';
    $header.= '<b>' . get_string('turno', 'sepex') . '</b>' . ': ' . $projeto[$idprojeto]->turno . '</br>';
    $header.= '<b>' . get_string('orientadores', 'sepex') . '</b>' . ': ' . implode(',', $professorcontroller->getNameProfessores($idprojeto, 'Orientador')) . '</br>';
    $header.= '<b>' . strtoupper(get_string('categoria', 'sepex')) . '</b>' . ': ' . $constantes->detailCategorias($projeto[$idprojeto]->idcategoria);
    $header .= html_writer::end_tag('div');
    echo $header;

    $apresentacao = $apresentacaocontroller->detailApresentacao($idprojeto);

    echo '<p>' . '<b>' . strtoupper(get_string('avaliadores', 'sepex')) . '</b>' . ': ' . implode(',', $professorcontroller->getNameProfessores($idprojeto, 'Avaliador')) . '</p>';
    echo '<p>' . '<b>' . get_string('local', 'sepex') . '</b>' . ':  ' . $apresentacao->nomelocalapresentacao . '</p>';
    echo '<p>' . '<b>' . get_string('apresentacao', 'sepex') . '</b>' . ':  ' . date("d/m/Y H:i:s", $apresentacao->dtapresentacao) . '</p></br>';

    if (isset($projeto[$idprojeto]->resumo)) {
        $resumo = html_writer::start_tag('div', array('style' => 'margin-left:5%; margin-right:10%;text-align:justify;'));
        $resumo .= html_writer::start_tag('p') . $projeto[$idprojeto]->resumo . html_writer::end_tag('p');
        $resumo .= html_writer::end_tag('div');
        echo $resumo;
        echo '<p></br>' . '<b>' . get_string('palavra_chave', 'sepex') . '</b>' . ':  ' . $projeto[$idprojeto]->tags . '</p>';
    }

    $avaliacao->display();

    if ($avaliacao->is_cancelled()) {
        redirect(VIEW_URL_LINK);
    } else if ($notas = $avaliacao->get_data()) {
        $avaliacaocontroller = new AvaliacaoController();

        if ($notas->update) {
            $notas->totalresumo = empty($notas->resumo1) ? null : $notas->resumo1 + $notas->resumo2 + $notas->resumo3 + $notas->resumo4 + $notas->resumo5;
            $notas->totalavaliacao = $notas->avaliacao1 + $notas->avaliacao2 + $notas->avaliacao3 + $notas->avaliacao4 + $notas->avaliacao5 + $notas->avaliacao6;
            $avaliacaocontroller->update($idprojeto, $notas);
        } else {
            $notas->totalresumo = empty($notas->resumo1) ? null : $notas->resumo1 + $notas->resumo2 + $notas->resumo3 + $notas->resumo4 + $notas->resumo5;
            $notas->totalavaliacao = empty($notas->avaliacao1) ? null : $notas->avaliacao1 + $notas->avaliacao2 + $notas->avaliacao3 + $notas->avaliacao4 + $notas->avaliacao5 + $notas->avaliacao6;
            $avaliacaocontroller->save($idprojeto, $notas);
        }

        $avaliacaocontroller->savePresencaAlunos($idprojeto, $notas->types);
        redirect(VIEW_URL_LINK);
    }
}

echo $OUTPUT->footer();
