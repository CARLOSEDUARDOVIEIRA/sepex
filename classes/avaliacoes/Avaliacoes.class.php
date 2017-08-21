<?php

/**
 * Classe responsavel por manter todas as avaliacoes referente a apresentacao dos projetos SEPEX
 *
 * @author Carlos Eduardo Vieira
 */
class Avaliacoes {

    /** Este metodo retorna os campos para avaliacao de resumo
     * @param type $mform - instancia do formulario moodleform
     */
    function createFormAvaliacaoResumo($mform) {

        $placeholder = '20 pontos';

        $mform->addElement('header', 'resumo_orientador', get_string('resumo', 'sepex'), array('size' => '15'));

        $mform->addElement('text', 'resumo1', get_string('qualidade_resumo', 'sepex'), array('placeholder' => '20 pontos', 'size' => '15'));
        $mform->addRule('resumo1', get_string('qualidade_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo1', get_string('qualidade_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo1', 'qualidade_resumo', 'sepex');
        $mform->setType('resumo1', PARAM_RAW);

        $mform->addElement('text', 'resumo2', get_string('objetivos_resumo', 'sepex'), array('placeholder' => $placeholder, 'size' => '15'));
        $mform->addRule('resumo2', get_string('objetivos_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo2', get_string('objetivos_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo2', 'objetivos_resumo', 'sepex');
        $mform->setType('resumo2', PARAM_RAW);

        $mform->addElement('text', 'resumo3', get_string('metodologia_resumo', 'sepex'), array('placeholder' => $placeholder, 'size' => '15'));
        $mform->addRule('resumo3', get_string('metodologia_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo3', get_string('metodologia_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo3', 'metodologia_resumo', 'sepex');
        $mform->setType('resumo3', PARAM_RAW);

        $mform->addElement('text', 'resumo4', get_string('resultados_resumo', 'sepex'), array('placeholder' => $placeholder, 'size' => '15'));
        $mform->addRule('resumo4', get_string('resultados_resumo', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo4', get_string('resultados_resumo', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo4', 'resultados_resumo', 'sepex');
        $mform->setType('resumo4', PARAM_RAW);

        $mform->addElement('text', 'resumo5', get_string('conclusao_objetivos', 'sepex'), array('placeholder' => $placeholder, 'size' => '15'));
        $mform->addRule('resumo5', get_string('conclusao_objetivos', 'sepex'), 'required', null, 'client');
        $mform->addRule('resumo5', get_string('conclusao_objetivos', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton('resumo5', 'conclusao_objetivos', 'sepex');
        $mform->setType('resumo5', PARAM_RAW);

        $mform->addElement('static', 'totalresumo', get_string('total_resumo', 'sepex'));
        $mform->setType('totalresumo', PARAM_RAW);
    }

    /** Metodo responsavel por criar os campos para o formulario de avaliacao de apresentacao SEPEX
     * @param type $mform - Instancia do formulario moodleform
     * @param type $field - Qual o nome do campo no bd
     * @param type $placeholder -Qual a mensagem de placeholder
     * @param type $string - As strings de titulo e de help
     */
    function createCampoAvaliacao($mform, $field, $placeholder, $string) {
        $mform->addElement('text', $field, get_string($string, 'sepex'), array('placeholder' => $placeholder, 'size' => '15'));
        $mform->addRule($field, get_string('required_number', 'sepex'), 'numeric', null, 'client');
        $mform->addHelpButton($field, $string, 'sepex');
        $mform->setType($field, PARAM_RAW);
    }

    /*     * Metodo responsavel por listar os alunos do projeto a ser avaliado de maneira que os avaliadores consigam
     * definir presenca a estes na apresentacao do projeto SEPEX
     * @param type $mform - Instancia do formulario moodleform
     * @param type $idprojeto - Id do projeto do qual se deseja listar os alunos
     */

    function getAlunos($mform, $idprojeto) {
        $alunocontroller = new AlunoController();
        $alunos = $alunocontroller->getNameAlunos($idprojeto);
        foreach ($alunos as $matraluno => $value) {
            $aluno = $alunocontroller->getPresencaAluno($idprojeto, $matraluno);
            $typeitem[] = &$mform->createElement('advcheckbox', $matraluno, '', $value, array('name' => $matraluno, 'group' => 1), array(0, 1));
            $mform->setDefault("types[$matraluno]", $aluno[$idprojeto]->presenca);
        }
        $mform->addGroup($typeitem, 'types', get_string('presenca_integrantes', 'sepex'));
        $mform->addHelpButton('types', 'presenca_integrantes', 'sepex');
    }

}
