<?php

require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require ('../controllers/AlunoController.class.php');
require ('../controllers/ProjetoController.class.php');
require ('../constantes/Constantes.class.php');
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

$PAGE->set_url('/mod/sepex/views/aluno.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

$alunocontroller = new AlunoController();
$projetocontroller = new ProjetoController();
$constantes = new Constantes();


echo $OUTPUT->header();

$showactivity = true;

$timenow = time();
if (!empty($sepex->timeavailablefrom) && $sepex->timeavailablefrom > $timenow) {
    echo $OUTPUT->notification(get_string('notopenyet', 'sepex', userdate($sepex->timeavailablefrom)));
    $showactivity = false;
} else if (!empty($sepex->timeavailableto) && $timenow > $sepex->timeavailableto) {
    echo $OUTPUT->notification(get_string('expired', 'sepex', userdate($sepex->timeavailableto)));
    $showactivity = false;
}

if ($showactivity) {
    $linkForm = html_writer::start_tag('div', array('id' => 'cabecalho', 'style' => 'margin-top:10%;'));
    $linkForm .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&add=1',));
    $linkForm .= html_writer::start_tag('submit', array('class' => 'btn btn-primary', 'style' => 'margin-bottom:5%;'));
    $linkForm .= get_string('inscricao', 'sepex');
    $linkForm .= html_writer::end_tag('a');
    $linkForm .= html_writer::end_tag('div');
    echo $linkForm;
}

echo '<table class="forumheaderlist table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>' . get_string('cod_projeto', 'sepex') . '</th>';
echo '<th>' . get_string('titulo_projeto', 'sepex') . '</th>';
echo '<th>' . get_string('categoria_projeto', 'sepex') . '</th>';
echo '<th>' . get_string('envio', 'sepex') . '</th>';
echo '<th> </th>';
echo '<th> </th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$projetos = $projetocontroller->getProjetosDoUsuario();
foreach ($projetos as $projeto) {
    echo '<tr>';
    echo'<td>' . $projeto->codprojeto . '</td>';

    $titulo = html_writer::start_tag('td');
    if ($showactivity) {
        $titulo .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&update=1',));
    } else {
        $titulo .= html_writer::start_tag('a', array('href' => './projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->id_projeto,));
    }
    $titulo .= $projeto->titulo;
    $titulo .= html_writer::end_tag('a');
    $titulo .= html_writer::end_tag('td');
    echo $titulo;

    echo'<td>' . $constantes->detailCategorias($projeto->idcategoria) . '</td>';

    echo'<td>' . $projeto->dtcadastro . '</td>';
    if ($showactivity) {
        
        $chat = html_writer::start_tag('td');
        $chat .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&delete=1',));
        $chat .= html_writer::start_tag('img', array('src' => '../pix/chat.png'));
        $chat .= html_writer::end_tag('a');
        $chat .= html_writer::end_tag('td');
        echo $chat;
        
        $editar = html_writer::start_tag('td');
        $editar .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&update=1',));
        $editar .= html_writer::start_tag('img', array('src' => '../pix/edit.png'));
        $editar .= html_writer::end_tag('a');
        $editar .= html_writer::end_tag('td');
        echo $editar;

        $delete = html_writer::start_tag('td');
        $delete .= html_writer::start_tag('a', array('href' => './cadastroProjeto.php?id=' . $id . '&idprojeto=' . $projeto->idprojeto . '&delete=1',));
        $delete .= html_writer::start_tag('img', array('src' => '../pix/delete.png'));
        $delete .= html_writer::end_tag('a');
        $delete .= html_writer::end_tag('td');
        echo $delete;
        
    } else {
        $link = html_writer::start_tag('td');
        $link .= html_writer::start_tag('a', array('href' => '../projeto_aluno/view.php?id=' . $id . '&data=' . $projeto->idprojeto,));
        $link .= get_string('visualizar', 'sepex');
        $link .= html_writer::end_tag('a');
        $link .= html_writer::end_tag('td');
        echo $link;
    }
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo $OUTPUT->footer();
