<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//Icons by:
//<div>Icons made by <a href="http://www.flaticon.com/authors/madebyoliver" title="Madebyoliver">Madebyoliver</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
/**
 * Biblioteca interna de funções para módulo sepex
 *
 * Todas as funções específicas do sepex, necessárias para implementar o módulo
 * Lógica, deve ir aqui. Nunca inclua este arquivo do seu lib.php!
 *
 * @package    mod_sepex
 * @copyright  2017 Carlos Eduardo Vieira <dullvieira@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

defined('MOODLE_INTERNAL') || die();

function enviar_email($USER, $dados) {
    global $PAGE;
    $date = new DateTime("now", core_date::get_user_timezone_object());
    $dataAtual = userdate($date->getTimestamp());
    $nao_responda = core_user::get_noreply_user();
    $PAGE->navbar->add('email');
    $constante = new Constantes();
    
//    $alunos = implode(";",$dados->aluno_matricula);          
    $titulo_email = 'Confirmação de inscrição XX SEPEX - UCV';
    $corpo_email = "Parabéns!\n"
            . "Você inscreveu seu trabalho na XX Semana de Ensino Pesquisa e Extensão - SEPEX"
            . "\nA partir de agora você pode acompanhar o status do seu projeto pelo moodle."
            . "\nCertifique-se de que a matrícula de todos os integrantes do seu grupo está"
            . " correta e que todos estão vendo o projeto em sua pagina principal de inscrição SEPEX "
            . "\nTítulo do trabalho:" . $dados->titulo
            . "\nCategoria: " . $constante->detailCategorias($dados->idcategoria)
            . "\nData de inscrição: " . $dataAtual;

    if (!$resultado = email_to_user($USER, $nao_responda, $titulo_email, $corpo_email)) {
        die("Erro no envio do email!");
    }
}
