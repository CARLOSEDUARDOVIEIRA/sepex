<?php

/* GERAR RESUMOS FORMATADOS PADRAO REVISTA */

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require('../classes/GerarResumoRevista.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../controllers/AlunoController.class.php');
require('../PHPWord.php');

$id = required_param('id', PARAM_INT);
$s = optional_param('s', 0, PARAM_INT);
$download = optional_param('download', 0, PARAM_INT);

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

define('VIEW_URL_LINK', "../view.php?id=" . $id);

$PAGE->set_url('/mod/sepex/views/GerarResumoRevista.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));


$categoria = new GerarResumoRevista("GerarResumoRevista.php?id={$id}&download=1");
if (empty($download)) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(strtoupper(get_string('resumo_revista', 'sepex')), 2);
    $categoria->display();
}

if ($categoria->is_cancelled()) {
    redirect(VIEW_URL_LINK);
} else if ($categoria->get_data()) {

    $projetocontroller = new ProjetoController();
    $alunocontroller = new AlunoController();
    $constantes = new Constantes();

    $projetos = $projetocontroller->getProjetosPorCategoria($categoria->get_data()->idcategoria);
    
    if (empty($projetos)) {
        echo $OUTPUT->header();
        echo $OUTPUT->notification(get_string('semprojeto', 'sepex'));
        echo $OUTPUT->footer();
        die();
    }

    $dtatual = date('Y');

    $footertext = "Semana de Pesquisa e Extensão - Revista de {$constantes->detailCategorias($categoria->get_data()->idcategoria)} do Centro Universitario Catolico de Vitoria do ES - {$dtatual} {PAGE}";

    $PHPWord = new PHPWord();

    $section = $PHPWord->createSection();

    $PHPWord->addTitleStyle(1, array('size' => 14, 'color' => 'black', 'bold' => true));
    $PHPWord->addTitleStyle(2, array('size' => 12, 'color' => 'black', 'bold' => true));

    foreach ($projetos as $resumo) {

        $header = $section->createHeader();
        $header->addPreserveText($constantes->detailCursos($resumo->idcurso), array('size' => 12, 'italic' => true));

        $section->addTitle(mb_strtoupper($resumo->titulo), 1);
        $section->addTextBreak(2);

        $section->addTitle('Aluno(s):', 2);
        $section->addText(implode(", ", $alunocontroller->getNameAlunos($resumo->idprojeto)), array('size' => 12));
        $section->addTextBreak(1);

        $section->addTitle('Coautor:', 2);
        $section->addText($resumo->orientador, array('size' => 12));
        $section->addTextBreak(2);

        $section->addTitle('Resumo', 2);
        $section->addText(strip_tags(mb_convert_encoding($resumo->resumo, 'UTF-8')), array('align' => 'justify', 'size' => 12));
        $section->addTextBreak(1);

        $tags = $section->createTextRun(null);
        $tags->addText('palavras-chave: ', array('size' => 12, 'bold' => true, 'color' => 'black'));
        $tags->addText($resumo->tags, array('size' => 12));

        $footer = $section->createFooter();
        $footer->addPreserveText($footertext, array('align' => 'left', 'size' => 7));

        $section->addPageBreak();
    }

    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save("Revista{$constantes->detailCategorias($categoria->get_data()->idcategoria)}.docx");

    $file = "Revista{$constantes->detailCategorias($categoria->get_data()->idcategoria)}.docx";
    header("Content-Disposition: attachment; filename={$file}");
    header("Content-Length: " . filesize($file));
    header("Content-Type: application/octet-stream;");
    readfile($file);
    unlink($file);
}
if (empty($download)) {
    echo $OUTPUT->footer();
}