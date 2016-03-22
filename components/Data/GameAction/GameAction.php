<?php

namespace Data\GameAction;


class GameAction {
	const DEFENSE_NAMES = array(
		'PORTCULLIS' => 'PC',
		'CHEVALDEFRISE' => 'CF',
		'MOAT' => 'MT',
		'RAMPARTS' => 'RP',
		'DRAWBRIDGE' => 'DB',
		'SALLYPORT' => 'SP',
		'ROCKWALL' => 'RW',
		'ROUGHTERRAIN' => 'RT',
		'LOWBAR' => 'LB'
	);
	
	const TOWER_SIDES = array(
		'LEFT',
		'CENTER',
		'RIGHT'
	);
	
	const TOWER_GOALS = array(
		'TOP',
		'BOTTOM'
	);
	
	const ASSIST_TYPES = array(
		'NONE',
		'PUSH',
		'OPEN'
	);
}