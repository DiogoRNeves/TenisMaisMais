<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Sobre';
$this->breadcrumbs = array(
    'Sobre',
);
?>
<div class="page-header">
    <h1>Sobre</h1>
</div>

<div class="row-fluid">
    <div class="span12">
        O <?php echo Yii::app()->name ?> é uma aplicação de código aberto que permite às equipas técnicas 
        dos clubes de ténis registar e acompanhar a evolução dos seus atletas. Esta aplicação está na
        fase de <span class="label label-important">protótipo</span>, tendo sido construída no âmbito 
        da tese de mestrado do autor.
        <div class="row-fluid">
            <div class="span6">
                <div class="page-header">
                    <h3>A aplicação</h3>
                </div>
                <p>
                    Para perceber a prioridade das funcionalidades que devem ser incorporadas nesta aplicação
                    foram efetuados questionários a treinadores do Centro de Ténis de Faro, do Olaias Tennis Club,
                    da Secção de Ténis da Associação de Moradores da Portela e da Secção de Ténis do Grupo 
                    Desportivo e Cultural do Banco de Portugal, em que estes treinadores identificaram a prioridade
                    de um conjunto de funcionalidades. Foram selecionadas para implementação em protótipo as primeiras doze
                    funcionalidades:
                </p>
                <ol>
                    <li>
                        Registo de atletas 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Registar treinadores 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Marcação de aulas, com vista de calendário associada 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Soluções optimizadas para mobile 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Integração com sítio na internet do clube já existente  
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Possibilidade de acesso dos atletas ao sistema 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Notificações perto de eventos importantes 
                        <span class="label label-important">Depende de 10</span>
                    </li>
                    <li>
                        Sistema de ranking 
                        <span class="label label-important">Depende de 12</span>
                    </li>
                    <li>
                        Compatível vários sistemas operativos desktop 
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Criar e ajustar os planeamentos competitivos para os vários grupos de atletas
                        <span class="label label-warning">Em desenvolvimento</span>
                    </li>
                    <li>
                        Sistema de controlo de assiduidade aos treinos e de contabilização 
                        de aulas de compensação
                        <span class="label label-success">OK</span>
                    </li>
                    <li>
                        Registo competitivo dos atletas (por torneio e por jogo)
                        <span class="label label-important">Aguarda desenvolvimento</span>
                    </li>
                </ol>
                <p>
                    <a href="https://github.com/DiogoRNeves/TenisMaisMais" target="_blank">
                        Ver projeto no GitHub
                    </a>
                </p>
            </div>
            <div class="span6">
                <div class="page-header">
                    <h3>O autor</h3>
                </div>
                <p>
                    Diogo Neves, <span class="label label-info">licenciado</span>
                    em Matemática pela Universidade do Algarve, <span class="label label-info">
                        pós-graduado</span> em Informática Aplicada às Organizações - Desenvolvimento de 
                    Sistemas de Informação pelo ISCTE-IUL e <span class="label label-info">mestrando
                    </span> em Informática Aplicada à Sociedade da Informação e Conhecimento 
                    no ISCTE-IUL.
                </p>
                <p>
                    Começou a jogar ténis aos 10 anos pelo Clube de Ténis de Pombal (Licença FPT nº 24214),
                    aos 14 mudou-se para o Centro de Ténis de Faro e aos 16 fez uma pausa no ténis, jogando 
                    voleibol dos 18 aos 23. Voltou a jogar ténis aos 25 anos no Olaias Tennis Club, onde
                    se mantém até à atualidade.
                </p>
                <p>
                    <a href="https://www.dropbox.com/s/h3fc2ewis6q4ovv/DiogoNeves_CurriculoPublico.pdf?dl=0" target="_blank">
                        Ver currículo
                    </a>
                </p>
            </div>                
        </div>
    </div>
</div>
