<?php

/**
 * Formulario de inscrição sepex, aqui são definidos os campos necessários para o cadastro dos projetos.
 *
 * @author Carlos Eduardo Vieira. Linkedin<>.
 */
require($CFG->dirroot . '/course/moodleform_mod.php');
require ('../constantes/Constantes.class.php');

class Formulario extends moodleform {

    function definition() {

        $constantes = new Constantes();
        $projetocontroller = new ProjetoController();
        
        $mform = $this->_form;
        $course = $this->_customdata['course'];
        $modcontext = $this->_customdata['modcontext'];

        if (isset($this->_customdata['modcontext'])) {
            $mform->setDefault('idcurso', $this->_customdata['idcurso']);
            $mform->setDefault('idperiodo', $this->_customdata['idperiodo']);
            $mform->setDefault('turno', $this->_customdata['turno']);
            $mform->setDefault('idcategoria', $this->_customdata['idcategoria']);
            $mform->setDefault('titulo', $this->_customdata['titulo']);
            $mform->setDefault('matraluno', $this->_customdata['matraluno']);
            $resumo = $this->_customdata['resumo'];
            $mform->setDefault('tags', $this->_customdata['tags']);
            $mform->setDefault('alocamesa', $this->_customdata['alocamesa']);
            $mform->setDefault('matrprofessor', $this->_customdata['matrprofessor']);
        }

        //CURSOS
        $cursos = $constantes->getCursos();
        $mform->addElement('select', 'idcurso', get_string('curso', 'sepex'), $cursos);
        $mform->addRule('idcurso', get_string('cursovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idcurso', 'curso', 'sepex');

        //PERIODOS
        $periodos = $constantes->getPeriodos();
        $mform->addElement('select', 'idperiodo', get_string('periodo', 'sepex'), $periodos);
        $mform->addRule('idperiodo', get_string('periodovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idperiodo', 'periodo', 'sepex');

        //TURNO
        $turnos = $constantes->getTurnos();
        $mform->addElement('select', 'turno', get_string('turno', 'sepex'), $turnos);
        $mform->addRule('turno', get_string('turnovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('turno', 'turno', 'sepex');

        //CATEGORIA
        $categorias = $constantes->getCategorias();
        $mform->addElement('select', 'idcategoria', get_string('categoria', 'sepex'), $categorias);
        $mform->addRule('idcategoria', get_string('categoriavazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('idcategoria', 'categoria', 'sepex');

        //TITULO DO TRABALHO
        $mform->addElement('text', 'titulo', get_string('titulo', 'sepex'), array('placeholder' => get_string('place_ajuda', 'sepex'), 'size' => '60'));
        $mform->setType('titulo', PARAM_RAW);
        $mform->addRule('titulo', get_string('titulovazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('titulo', get_string('titulo', 'sepex', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('titulo', 'titulo', 'sepex');


        //MATRICULA DO ALUNO                           
        $mform->addElement('text', 'matraluno', get_string('integrantes', 'sepex'), array('placeholder' => get_string('place_ajuda', 'sepex'), 'size' => '60'));
        $mform->setType('matraluno', PARAM_RAW);
        $mform->addRule('matraluno', get_string('integrantevazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('matraluno', 'integrantes', 'sepex');

        //ORIENTADOR
        $orientadores = $projetocontroller->getUsuarioPorCurso('editingteacher', $course);
        $professores = array('' => 'Escolher',);
        foreach ($orientadores as $professor) {
            $professores[$professor->username] = $professor->name;
        }
        $mform->addElement('select', 'matrprofessor', get_string('orientador', 'sepex'), $professores);
        $mform->addRule('matrprofessor', get_string('orientadorvazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('matrprofessor', 'orientador', 'sepex');

        //RESUMO                
        $mform->addElement('editor', 'resumo', get_string('resumo', 'sepex'), null, array('context' => $modcontext))->setValue(array('text' => $resumo));
        $mform->addRule('resumo', get_string('resumovazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('resumo', 'resumo', 'sepex');
        $mform->setType('resumo', PARAM_RAW);

        //TAGS       
        $mform->addElement('text', 'tags', get_string('tags', 'sepex'), array('placeholder' => 'Clique em (?) ao lado para obter ajuda.', 'size' => '60'));
        $mform->setType('tags', PARAM_RAW);
        $mform->addRule('tags', get_string('tagsvazio', 'sepex'), 'required', null, 'client');
        $mform->addRule('tags', get_string('tags', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('tags', 'tags', 'sepex');

        //ALOCA MESA
        $mesa = array(
            '' => 'Escolher',
            '1' => 'Eu desejo uma mesa',
            '0' => 'Não desejo uma mesa',
        );
        $mform->addElement('select', 'alocamesa', get_string('alocamesa', 'sepex'), $mesa);
        $mform->addRule('alocamesa', get_string('alocamesavazio', 'sepex'), 'required', null, 'client');
        $mform->addHelpButton('alocamesa', 'alocamesa', 'sepex');

        $this->add_action_buttons($cancel = true, get_string('btnEnviar', 'sepex'));
    }

    function validation($data, $files) {
        return array();
    }

}
