<?php
/* @var $this CompetitivePlanController
 * @var $model AthleteGroup
 * @var $federationTournamentSearch FederationTournament
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array(
        'id' => 'searchTournament',
        )
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Adicionar Torneio</h4>
    </div>

    <div class="modal-body">

        <div class="well clearfix">
            <?php
            $this->renderPartial('_tournamentSearchForm', array(
                'model' => $federationTournamentSearch,
            ));
            ?>
        </div>

        <?php
            $this->renderPartial('_tournamentSearchResults', array(
                'model' => $model,
                'federationTournamentSearch' => $federationTournamentSearch,
            ));
        ?>

        <div class="modal-footer">
            <?php $this->widget(
                'booster.widgets.TbButton',
                array(
                    'label' => 'Fechar',
                    'url' => '#',
                    'htmlOptions' => array('data-dismiss' => 'modal'),
                )
            ); ?>
        </div>
    </div>

<?php $this->endWidget(); //end modal?>