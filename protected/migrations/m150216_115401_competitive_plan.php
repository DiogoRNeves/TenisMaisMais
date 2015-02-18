<?php

class m150216_115401_competitive_plan extends CDbMigration
{
    public function up()
    {
		$athleteGroup = 'AthleteGroup';
		$this->dropPrimaryKey('PRIMARY', $athleteGroup);
		$this->alterColumn($athleteGroup, 'athleteGroupID', 'pk');
		$this->addColumn($athleteGroup, 'friendlyName', 'varchar(60) not null');
		$this->addColumn($athleteGroup, 'includeMale', 'boolean not null');
		$this->addColumn($athleteGroup, 'includeFemale', 'boolean not null');
		$this->dropColumn($athleteGroup, 'minPlayerLevel');
		$this->addColumn($athleteGroup, 'minPlayerLevelID', 'integer');
		$this->addForeignKey('fk_AthleteGroup_Club2', $athleteGroup, 'minPlayerLevelID', 'PlayerLevel', 'playerLevelID');
		$this->dropColumn($athleteGroup, 'maxPlayerLevel');
		$this->addColumn($athleteGroup, 'maxPlayerLevelID', 'integer');
		$this->addForeignKey('fk_AthleteGroup_Club3', $athleteGroup, 'maxPlayerLevelID', 'PlayerLevel', 'playerLevelID');
		$this->addColumn($athleteGroup, 'active', 'boolean not null default 1');
		
		$this->dropTable('TournamentType');
		
		$this->alterColumn('FederationTournament', 'city', 'varchar(150)');

		$federationClub = 'FederationClub';
		$this->dropPrimaryKey('PRIMARY', $federationClub);
		$this->alterColumn($federationClub, 'federationClubID', 'pk');
		
		$tournamentVariation = 'TournamentVariation';
		$this->createTable($tournamentVariation, array(
			'tournamentVariationID' => 'pk',
			'abbreviation' => 'VARCHAR(3) UNIQUE NOT NULL',
			'text' => 'VARCHAR(50) UNIQUE NOT NULL',
			'singles' => 'boolean not null',
			'allowMale' => 'boolean not null',
			'allowFemale' => 'boolean not null',
		));
		$this->createIndex('idx_unique_combo', $tournamentVariation, 'singles,allowFemale,allowMale', true);

		$this->insert($tournamentVariation, array(
			'abbreviation' => 'SM',
			'text' => 'Singulares Masculinos',
			'singles' => true,
			'allowMale' => true,
			'allowFemale' => false,
		));
		$this->insert($tournamentVariation, array(
			'abbreviation' => 'SF',
			'text' => 'Singulares Femininos',
			'singles' => true,
			'allowMale' => false,
			'allowFemale' => true,
		));
		$this->insert($tournamentVariation, array(
			'abbreviation' => 'PM',
			'text' => 'Pares Masculinos',
			'singles' => false,
			'allowMale' => true,
			'allowFemale' => false,
		));
		$this->insert($tournamentVariation, array(
			'abbreviation' => 'PF',
			'text' => 'Pares Femininos',
			'singles' => false,
			'allowMale' => false,
			'allowFemale' => true,
		));
		$this->insert($tournamentVariation, array(
			'abbreviation' => 'PMi',
			'text' => 'Pares Mistos',
			'singles' => false,
			'allowMale' => true,
			'allowFemale' => true,
		));
		
		$federationTournamentHasAgeBand = 'FederationTournamentHasAgeBand';
		$this->addColumn($federationTournamentHasAgeBand, 'tournamentVariationID', 'integer not null');
		$this->addForeignKey('fk_FederationTournament_has_AgeBand_TournamentVariation1_idx', $federationTournamentHasAgeBand, 'tournamentVariationID', $tournamentVariation, 'tournamentVariationID');
		$this->dropPrimaryKey('PRIMARY', $federationTournamentHasAgeBand);
		$this->addPrimaryKey('PRIMARY', $federationTournamentHasAgeBand, 'federationTournamentID,ageBandID,tournamentVariationID');
		
		$ageBandGroup = 'AgeBandGroup';
		$this->createTable($ageBandGroup, array(
			'ageBandGroupID' => 'pk',
			'name' => 'VARCHAR(30) NOT NULL',
		));

		$this->insert($ageBandGroup, array(
			'name' => 'Juvenil',
		));
		$this->insert($ageBandGroup, array(
			'name' => 'Sénior',
		));
		$this->insert($ageBandGroup, array(
			'name' => 'Veteranos',
		));
		
		$ageBand = 'AgeBand';
		$this->alterColumn($ageBand, 'minAge', 'integer');
		$this->alterColumn($ageBand, 'maxAge', 'integer');
		$this->dropPrimaryKey('PRIMARY', $ageBand);
		$this->alterColumn($ageBand, 'ageBandID', 'pk');
		$this->addColumn($ageBand, 'ageBandGroupID', 'integer not null');
		$this->addForeignKey('fk_AgeBand_AgeBandGroup1_idx', $ageBand, 'ageBandGroupID', $ageBandGroup, 'ageBandGroupID');

		$this->insert($ageBand, array(
			'name' => 'SUB 10',
			'maxAge' => 10,
			'ageBandGroupID' => 1,
		));
		$this->insert($ageBand, array(
			'name' => 'SUB 12',
			'maxAge' => 12,
			'ageBandGroupID' => 1,
		));
		$this->insert($ageBand, array(
			'name' => 'SUB 14',
			'maxAge' => 14,
			'ageBandGroupID' => 1,
		));
		$this->insert($ageBand, array(
			'name' => 'SUB 16',
			'maxAge' => 16,
			'minAge' => 11,
			'ageBandGroupID' => 1,
		));
		$this->insert($ageBand, array(
			'name' => 'SUB 18',
			'maxAge' => 18,
			'minAge' => 13,
			'ageBandGroupID' => 1,
		));
		$this->insert($ageBand, array(
			'name' => 'Seniores',
			'minAge' => 14,
			'ageBandGroupID' => 2,
		));
		$this->insert($ageBand, array(
			'name' => '+30',
			'minAge' => 30,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+35',
			'minAge' => 35,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+40',
			'minAge' => 40,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+45',
			'minAge' => 45,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+50',
			'minAge' => 50,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+55',
			'minAge' => 55,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+60',
			'minAge' => 60,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+65',
			'minAge' => 65,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+70',
			'minAge' => 70,
			'ageBandGroupID' => 3,
		));
		$this->insert($ageBand, array(
			'name' => '+75',
			'minAge' => 75,
			'ageBandGroupID' => 3,
		));
    }
	
    public function down()
    {
		$athleteGroup = 'AthleteGroup';
		$this->dropColumn($athleteGroup, 'friendlyName');
		$this->dropForeignKey('fk_AthleteGroup_Club2', $athleteGroup);
		$this->dropColumn($athleteGroup, 'includeMale');
		$this->dropColumn($athleteGroup, 'includeFemale');
		$this->addColumn($athleteGroup, 'minPlayerLevel', 'string');
		$this->dropForeignKey('fk_AthleteGroup_Club3', $athleteGroup);
		$this->dropColumn($athleteGroup, 'minPlayerLevelID');
		$this->addColumn($athleteGroup, 'maxPlayerLevel', 'string');
		$this->dropColumn($athleteGroup, 'maxPlayerLevelID');
		$this->dropColumn($athleteGroup, 'active');

		$tournamentType = 'TournamentType';
		$this->createTable($tournamentType, array(
			'variation' => 'text',
			'gender' => 'text',
			'federationTournamentID' => 'integer not null',
		));
		$this->addPrimaryKey('PRIMARY',$tournamentType, 'variation,gender,federationTournamentID');
		$this->addForeignKey('fk_TournamentType_FederationTournament1_idx', $tournamentType, 'federationTournamentID', 'FederationTournament', 'federationTournamentID');

		$this->dropTable('TournamentVariation');

		$federationTournamentHasAgeBand = 'FederationTournamentHasAgeBand';
		$this->dropForeignKey('fk_FederationTournament_has_AgeBand_TournamentVariation1_idx', $federationTournamentHasAgeBand);
		$this->dropColumn($federationTournamentHasAgeBand, 'tournamentVariationID');
		$this->dropPrimaryKey('PRIMARY', $federationTournamentHasAgeBand);
		$this->addPrimaryKey('PRIMARY', $federationTournamentHasAgeBand, 'federationTournamentID,ageBandID');

		$this->dropTable('AgeBandGroup');

		$ageBand = 'AgeBand';
		$this->dropForeignKey('fk_AgeBand_AgeBandGroup1_idx', $ageBand);
		$this->dropColumn($ageBand, 'ageBandGroupID');
    }
}