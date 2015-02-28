<?php
 
class m150226_180224_search_by_tournament_distance extends CDbMigration
{
    public function up()
    {
        $landPhonePrefixes = 'LandPhonePrefixes';
        $this->createTable($landPhonePrefixes, array(
            'prefix' => 'integer NOT NULL',
            'zone' => 'string NOT NULL',
        ));
        $this->addPrimaryKey('PRIMARY', $landPhonePrefixes, 'prefix');
		
        $this->insert($landPhonePrefixes, array(
            'prefix' => 21,
            'zone' => 'Lisboa',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 22,
            'zone' => 'Porto',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 231,
            'zone' => 'Mealhada',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 232,
            'zone' => 'Viseu',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 233,
            'zone' => 'Figueira da Foz',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 234,
            'zone' => 'Aveiro',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 235,
            'zone' => 'Arganil',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 236,
            'zone' => 'Pombal',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 238,
            'zone' => 'Seia',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 239,
            'zone' => 'Coimbra',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 241,
            'zone' => 'Abrantes',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 242,
            'zone' => 'Ponte de Sor',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 243,
            'zone' => 'Santarém',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 244,
            'zone' => 'Leiria',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 245,
            'zone' => 'Portalegre',
        ));
        $this->insert($landPhonePrefixes, array(
            'prefix' => 249,
            'zone' => 'Torres Novas',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 251,
            'zone' => 'Torres Novas',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 252,
            'zone' => 'Vila Nova de Famalicão',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 253,
            'zone' => 'Braga',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 254,
            'zone' => 'Peso da Régua',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 255,
            'zone' => 'Penafiel',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 256,
            'zone' => 'São João da Madeira',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 258,
            'zone' => 'Viana do Castelo',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 259,
            'zone' => 'Vila Real',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 261,
            'zone' => 'Torres Vedras',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 262,
            'zone' => 'Caldas da Raínha',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 263,
            'zone' => 'Vila Franca de Xira',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 265,
            'zone' => 'Setúbal',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 266,
            'zone' => 'Évora',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 268,
            'zone' => 'Estremoz',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 269,
            'zone' => 'Santiago do Cacém',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 271,
            'zone' => 'Guarda',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 272,
            'zone' => 'Castelo Branco',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 273,
            'zone' => 'Bragança',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 274,
            'zone' => 'Proença a Nova',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 275,
            'zone' => 'Covilhã',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 276,
            'zone' => 'Chaves',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 277,
            'zone' => 'Idanha a Nova',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 278,
            'zone' => 'Mirandela',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 279,
            'zone' => 'Torre de Moncorvo',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 281,
            'zone' => 'Tavira',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 282,
            'zone' => 'Portimão',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 283,
            'zone' => 'Odemira',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 284,
            'zone' => 'Beja',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 285,
            'zone' => 'Moura',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 286,
            'zone' => 'Castro Verde',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 289,
            'zone' => 'Faro',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 291,
            'zone' => 'Funchal',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 292,
            'zone' => 'Horta',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 295,
            'zone' => 'Angra do Heroísmo',
        ));
		$this->insert($landPhonePrefixes, array(
            'prefix' => 296,
            'zone' => 'Ponta Delgada',
        ));
		
		$localCoordinateCache = 'LocalCoordinateCache';
		$this->createTable($localCoordinateCache, array(
			'localCoordinateCacheID' => 'pk',
			'coordinatesSearchString' => 'string not null',
			'lat' => 'float not null',
			'lng' => 'float not null',
		));
		$this->createIndex('idx_unique_searchString',$localCoordinateCache, 'coordinatesSearchString', true);
		
		$federationTournament = 'FederationTournament';
		$this->addColumn($federationTournament, 'localCoordinateCacheID', 'integer');
		$this->addForeignKey('idx_fk_LocalCoordinateCache',$federationTournament, 'localCoordinateCacheID', $localCoordinateCache, 'localCoordinateCacheID');
    }
 
    public function down()
    {
        $this->dropTable('LandPhonePrefixes');
        $this->dropTable('LocalCoordinateCache');
		
		$this->dropForeignKey('idx_fk_LocalCoordinateCache','FederationTournament');
		$this->dropColumn('FederationTournament', 'localCoordinateCacheID');
    }
 
    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }
 
    public function safeDown()
    {
    }
    */
}
