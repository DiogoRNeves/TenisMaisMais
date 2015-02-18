<?php

class m150217_140642_not_tennis_tournaments extends CDbMigration
{
	public function up()
	{
		$this->createTable('NotTennisTournaments', array(
			'federationTournamentID' => 'pk',
		));
	}

	public function down()
	{
		$this->dropTable('NotTennisTournaments');
	}
}