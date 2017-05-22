<?php
/**
* TELA APRESENTADA AO PROFESSOR AVALIADOR PARA AVALIAÇÃO DO PROJETO 
 *
 * @package    mod_sepex
 * @copyright  2017 Marcos Vinicius A. Moreira  <marcosv_3@hotmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('../locallib.php');

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
$event = \mod_sepex\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $sepex);
$event->trigger();
$PAGE->set_url('/mod/sepex/Avaliacao.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sepex->name));
$PAGE->set_heading(format_string($sepex->name));

$idProjeto = 29;

$projeto = listar_projeto_por_id($idProjeto);
$categoria = retorna_categoria($projeto[$idProjeto]->cod_categoria);
$alunos = listarMatriculaAlunos($idProjeto);

echo $OUTPUT->header();

$formulario = html_writer::start_tag('form', array('id' => 'avalicaoSepex', 'action'=> "acao_avaliacao.php?id={$id}", 'method'=>"POST"));
    $linkForm = html_writer::start_tag('div', array('id' => 'cabeçalho', 'style' => 'margin-top: 10%;border-style: solid;', 'class="container-fluid"'));
    
    //TÍTULO
        $linkForm .= html_writer::start_tag('header', array('class' => 'row;'));
            $linkForm .= html_writer::start_tag('div', array('class' => 'page-header'));
                $linkForm .= html_writer::start_tag('center');
                $linkForm .= html_writer::start_tag('h1');
                $linkForm .= 'Avaliar resumo';
            $linkForm .= html_writer::end_tag('div'); 
        $linkForm .= html_writer::end_tag('header');
    
    //SUBTÍTULO
        $linkForm .= html_writer::start_tag('div', array('class' => 'main'));
            $linkForm .= html_writer::start_tag('center');
            $linkForm .= html_writer::start_tag('h3');
            $linkForm .= 'Codigo do projeto / nome do projeto';
            $linkForm .= html_writer::end_tag('h3');
            $linkForm .= html_writer::start_tag('hr');
                $linkForm .= html_writer::start_tag('div', array('class' => 'container-fluid'));
                    $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                        $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                            $linkForm .= html_writer::start_tag('label');
                                $linkForm .= 'Resumo:';
                            $linkForm .= html_writer::end_tag('label');
                            $linkForm .= html_writer::start_tag('p', array('align class' => 'text-justify'));
                                $linkForm .= $projeto[$idProjeto]->resumo;
                              //  $linkForm .= 'Com o aumento da utilização de aplicações web para a realização de tarefas online, que vão desde uma simples pesquisa à compra de produtos e pagamentos de contas, juntamente com o constante crescimento das plataformas de conteúdo multimídia, percebemos a dificuldade que grande parcela das pessoas que utilizam a internet tem para encontrar conteúdos ou produtos que sejam de seu interesse. Mediante esse contexto, surgiu a ideia de desenvolver um Sistema de Recomendação de Filmes em que será disponibilizado ao usuário uma lista de filmes, podendo realizar a filtragem de acordo com o gênero, devendo o usuário selecionar um filme e atribuir a ele uma nota de 1 a 5. Após a atribuição das notas, por meio de uma variação do algoritmo SlopeOne (método de recomendação subdividido da Filtragem Colaborativa que se vale do feedback efetivo) serão recomendados filmes ao usuário de acordo com o seu perfil. Para o desenvolvimento do sistema foram utilizados os conhecimentos práticos e teóricos adquiridos nas disciplinas: Programação Orientada a Objetos I, utilizando a IDE NetBeans e a linguagem JAVA. Na disciplina Fundamentos de Banco de Dados foi modelada a base de dados utilizando a ferramenta MySQL Workbench e em Projetos de Sistemas foi confeccionada toda a documentação. Pretendemos com esse sistema analisar o comportamento do algoritmo de recomendação diante da quantidade de dados utilizada para cálculo, buscando recomendar filmes que efetivamente sejam de interesse do usuário. Para implementações futuras, o sistema será atualizado para operar em ambiente web.';
                            $linkForm .= html_writer::end_tag('p');
                        $linkForm .= html_writer::end_tag('div');

                    //CRITÉRIOS
                        $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                            $linkForm .= html_writer::start_tag('div', array('class' => 'input-group'));
                                $linkForm .= html_writer::start_tag('table', array('class' => 'table table-responsive'));
                                    $linkForm .= html_writer::start_tag('thead');
                                        $linkForm .= html_writer::start_tag('tr');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Quesito';
                                            $linkForm .= html_writer::end_tag('th');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Valor';
                                            $linkForm .= html_writer::end_tag('th');
                                            $linkForm .= html_writer::start_tag('th', array('scope' => 'col'));
                                                $linkForm .= 'Nota';
                                            $linkForm .= html_writer::end_tag('th');
                                        $linkForm .= html_writer::end_tag('tr');
                                    $linkForm .= html_writer::end_tag('thead');
                    //--------------------------------------------------------------------------------------------                
                                    $linkForm .= html_writer::start_tag('tfoot');
                                    $linkForm .= html_writer::end_tag('tfoot');

                                    $idz = $projeto[$idProjeto]->cod_categoria;
                                    switch($idz){
                                        case 1:
                                        case 3:
                                        case 5:
                                        case 8:
                                    //EGRESSOS OU INICIAÇÃO CIENTÍFICA OU EXTENSÃO OU TEMAS LIVRES
                                            $linkForm .= html_writer::start_tag('tbody');
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Exposição com clareza e objetividade';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '25';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item01'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Cumprimento do limite de tempo da apresentação (10 minutos)';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '25';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item02'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Demonstração de segurança e conhecimento';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '25';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item03'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Capacidade de demonstrar a relevância do trabalho';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '25';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item04'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Total';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    /*
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('label');
                                                            $linkForm .= 
                                                        $linkForm .= html_writer::end_tag('label');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    */
                                                $linkForm .= html_writer::end_tag('tr');
                                            $linkForm .= html_writer::end_tag('tbody');
                                            
                                            $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => 'acao', 'value' => '1'));
                                            $linkForm .= html_writer::end_tag('input');
                                    //---------------------------------------------------------------------------------------------
                                        break;
                                        case 6:
                                    //PROJETO INTEGRADOR
                                            $linkForm .= html_writer::start_tag('tbody');
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Exposição com clareza e objetividade';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'case20'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Cumprimento do limite de tempo da apresentação (10 minutos)';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'case21'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Demonstração de segurança e conhecimento';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'case22'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Percepção do aluno quanto à interdisciplinaridade';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'case23'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Capacidade de demonstrar a relevância do trabalho';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'case24'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                /*
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Total';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');

                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('label');
                                                            $linkForm .= 'TOTAL resultado';
                                                        $linkForm .= html_writer::end_tag('label');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    */
                                                $linkForm .= html_writer::end_tag('tr');
                                            $linkForm .= html_writer::end_tag('tbody');

                                            $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => 'acao', 'value' => '2'));
                                            $linkForm .= html_writer::end_tag('input');
                                    //--------------------------------------------------------------------------------------------
                                        break;
                                        case 2:
                                    //ESTÁGIO
                                            $linkForm .= html_writer::start_tag('tbody');
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Exposição com clareza e objetividade';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item01'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Cumprimento do limite de tempo da apresentação (10 minutos)';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item02'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Demonstração de segurança e conhecimento';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item03'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Valorização dos conhecimentos teóricos frente ao desenvolvimento do trabalho';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item04'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Capacidade de demonstrar a relevância do estágio frente às necessidades do mercado';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item05'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');
                                                
                                                /*
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Total';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('label');
                                                            $linkForm .= 'TOTAL resultado';
                                                        $linkForm .= html_writer::end_tag('label');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    */
                                                $linkForm .= html_writer::end_tag('tr');
                                            $linkForm .= html_writer::end_tag('tbody');

                                            $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => 'acao', 'value' => '3'));
                                            $linkForm .= html_writer::end_tag('input');
                                    //----------------------------------------------------------------------------
                                        break;
                                        case 4:
                                    //INOVAÇÃO E EMPREENDEDORISMO
                                            $linkForm .= html_writer::start_tag('tbody');
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Exposição com clareza e objetividade';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item01'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Cumprimento do limite de tempo da apresentação (10 minutos)';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item02'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Demonstração de segurança e conhecimento';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item03'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Capacidade de demonstrar a relevância do trabalho';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item04'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Viabilidade do trabalho';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item05'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                /*
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Total';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('label');
                                                            $linkForm .= 'TOTAL resultado';
                                                        $linkForm .= html_writer::end_tag('label');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    */
                                                $linkForm .= html_writer::end_tag('tr');
                                            $linkForm .= html_writer::end_tag('tbody');

                                            $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => 'acao', 'value' => '4'));
                                            $linkForm .= html_writer::end_tag('input');
                                    //----------------------------
                                        break;
                                        default:
                                    //RESUMO
                                            $linkForm .= html_writer::start_tag('tbody');
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Qualidade da redação e organização do texto';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item01'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Objetivos claros';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item02'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Descrição clara da metodologia';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item03'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Qualidade dos resultados';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item04'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Adequação da conclusão aos objetivos propostos';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= '20';
                                                    $linkForm .= html_writer::end_tag('td');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('input', array('class' => 'form-control', 'type' => 'text', 'rows' => '1', 'name' => 'item05'));
                                                    $linkForm .= html_writer::end_tag('td');
                                                $linkForm .= html_writer::end_tag('tr');

                                                /*
                                                $linkForm .= html_writer::start_tag('tr');
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('b');
                                                            $linkForm .= 'Total';
                                                        $linkForm .= html_writer::end_tag('b');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    
                                                    $linkForm .= html_writer::start_tag('td');
                                                        $linkForm .= html_writer::start_tag('label');
                                                            $linkForm .= 'TOTAL';
                                                        $linkForm .= html_writer::end_tag('label');
                                                    $linkForm .= html_writer::end_tag('td');
                                                    */
                                                $linkForm .= html_writer::end_tag('tr');
                                            $linkForm .= html_writer::end_tag('tbody');    

                                            $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => 'acao', 'value' => '5'));
                                            $linkForm .= html_writer::end_tag('input');
                                    //-----------------------------
                                        break;
                                    }

                                    $linkForm .= html_writer::end_tag('table');

                                   $CheckId = 0;
                                foreach ($alunos as $aluno){
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-11'));
                                            $linkForm .= html_writer::start_tag('input', array('type' => 'checkbox', 'name' => 'CheckAluno'."$CheckId".'', 'value' => ''."$aluno".''));
                                            $linkForm .= html_writer::end_tag('input');
                                            $linkForm .= $aluno;
                                            $CheckId = $CheckId + 1;
                                    $linkForm .= html_writer::end_tag('div');
                                }

                            //INPUT PARA ENVIAR A QUANTIDADE DE ALUNO PARA A PAGINA ACAO_AVALIACAO
                                $linkForm .= html_writer::start_tag('input', array('type' => 'hidden', 'name' => "qtdAlunos", 'value' => ''."$CheckId".''));
                                $linkForm .= html_writer::end_tag('input');
                            //--------------------------------------------------------------------

                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
                                $linkForm .= html_writer::start_tag('div', array('class' => 'row'));
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-6'));
                                    $linkForm .= html_writer::start_tag('input', array('type' => 'submit', 'class' => 'btn btn-active btn-lg', 'value' => 'Voltar'));
                                    $linkForm .= html_writer::end_tag('div');
                                    $linkForm .= html_writer::start_tag('div', array('class' => 'col-md-3'));
                                        $linkForm .= html_writer::start_tag('input', array('type' => 'submit', 'class' => 'btn btn-active btn-lg', 'value' => 'Enviar'));
                                    $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::end_tag('div');
                                $linkForm .= html_writer::start_tag('br');
                                $linkForm .= html_writer::end_tag('br');
                            $linkForm .= html_writer::end_tag('div');
                        $linkForm .= html_writer::end_tag('div');
                    $linkForm .= html_writer::end_tag('div');
                $linkForm .= html_writer::end_tag('div');
            $linkForm .= html_writer::end_tag('hr');
        $linkForm .= html_writer::end_tag('div');
    $linkForm .= html_writer::end_tag('div'); //segunda DIV

        $formulario .= $linkForm;
 $formulario .= html_writer::end_tag('form');

    echo $formulario;

//Fim da página
echo $OUTPUT->footer();