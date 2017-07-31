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
        '10' => 'Mostra de Vídeos',
        '11' => 'Concurso de Fotografia'
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

}
