<?php

/**
 * Esta pagina eh responsavel por enviar uma mensagem dos professores orientadores para os alunos
 * com o feedback dos resumos dos projetos.
 *
 * @author Carlos Eduardo Vieira
 */
class SendMessage {

    public function send($codprojeto,$titulo, $alunos, $statusresumo, $obsorientador) {
        global $USER, $DB;
        
        $status = $statusresumo = 0 ? 'Aprovado' : 'Reprovado';

        foreach (explode(';', $alunos) as $aluno) {
            $user = $DB->get_record('user', array('username' => $aluno));
            $message = new \core\message\message();
            $message->component = 'moodle';
            $message->name = 'instantmessage';
            $message->userfrom = $USER;
            $message->userto = $user;
            $message->subject = 'message subject 1';
            $message->fullmessage = 'message body';
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml = '<p>message body</p>';
            $message->smallmessage = "Mensagem referente ao projeto {$codprojeto} - {$titulo}. </br> <b>Seu projeto esta {$status}.</b> </br> <b>Feedback do orientador: </b> </br> {$obsorientador}";
            $message->notification = '0';
            //$message->contexturl = 'http://GalaxyFarFarAway.com';
            //$message->contexturlname = 'Context name';
            $message->replyto = $USER->email;
            //$content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
            //$message->set_additional_content('email', $content);
            message_send($message);
        }
        return true;
    }

}