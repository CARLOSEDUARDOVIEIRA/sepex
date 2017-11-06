<?php

/* * * Classe responsavel por guardar todas as variaveis <b>constantes</b> do sistema sepex
 *  Variaveis constantes sao variaveis consideradas na maioria dos casos imutaveis.
 * @author Carlos Eduardo Vieira
 */

class Constantes {

    const periodos = array(
        '' => 'Escolher',
        '1' => 'Primeiro Período',
        '2' => 'Segundo Período',
        '3' => 'Terceiro Período',
        '4' => 'Quarto Período',
        '5' => 'Quinto Período',
        '6' => 'Sexto Período',
        '7' => 'Sétimo Período',
        '8' => 'Oitavo Período',
        '9' => 'Nono Período',
        '10' => 'Décimo Período',
    );
    const turnos = array(
        '' => 'Escolher',
        'Matutino' => 'Matutino',
        'Noturno' => 'Noturno',
    );

    /*     * Caso este codigo mude, altere tambem o metodo <b>createCodigoProjeto</b> em projeto model */
    const categorias = array(
        '' => 'Escolher',
        '1' => 'Egressos',
        '2' => 'Estágios',
        '3' => 'Iniciação Científica',
        '4' => 'Inovação',
        '5' => 'Projeto de Extensão',
        '6' => 'Projeto Integrador',
        '7' => 'Responsábilidade Social',
        '8' => 'Temas Livres',
        '9' => 'Trabalho de Conclusão de Curso',
        '10' => 'Mostra de Vídeos'        
    );
    const cursos = array(
        '' => 'Escolher',
        '1' => 'Administração',
        '2' => 'Arquitetura e Urbanismo',
        '3' => 'Ciências Biológicas (Bacharelado)',
        '4' => 'Ciências Biológicas (Licenciatura)',
        '5' => 'Ciências Contábeis',
        '6' => 'Tecnologia em design de interiores',
        '7' => 'Direito',
        '8' => 'Educação Física',
        '9' => 'Enfermagem',
        '10' => 'Engenharia Civil',
        '11' => 'Engenharia de Produção',
        '12' => 'Farmácia',
        '13' => 'Filosofia',
        '14' => 'Fisioterapia',
        '15' => 'Nutrição',
        '16' => 'Psicologia',
        '17' => 'Serviço Social',
        '18' => 'Sistemas de Informação',
        '19' => 'Tecnologia em Análise e Desenvolvimento de Sistemas',
        '20' => 'Tecnologia em Logística',
        '21' => 'Tecnologia em Redes de Computadores'
    );
    const areacurso = array(
        '' => 'Escolher',
        '1' => 1,
        '2' => 1,
        '3' => 3,
        '4' => 3,
        '5' => 1,
        '6' => 1,
        '7' => 1,
        '8' => 1,
        '9' => 3,
        '10' => 2,
        '11' => 2,
        '12' => 3,
        '13' => 1,
        '14' => 3,
        '15' => 3,
        '16' => 1,
        '17' => 1,
        '18' => 2,
        '19' => 2,
        '20' => 2,
        '21' => 2
    );
    const areas = array(
        '' => 'Escolher',
        '1' => 'Ciências Sociais e Aplicadas',
        '2' => 'Exatas',
        '3' => 'Saúde'
    );

    public static function getPeriodos() {
        return self::periodos;
    }

    public static function getTurnos() {
        return self::turnos;
    }

    public static function getCategorias() {
        return self::categorias;
    }

    public static function getCursos() {
        return self::cursos;
    }

    public static function getAreas() {
        return self::areas;
    }

    public static function detailCategorias($idcategoria) {
        return self::categorias[$idcategoria];
    }

    public static function detailCursos($idcurso) {
        return self::cursos[$idcurso];
    }

    public static function getAreaCurso($idcurso) {
        return self::areacurso[$idcurso];
    }
    public static function detailAreas($area){
        return self::areas[$area];
    }

}
