<?php

/**
 *
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Token extends AppModel {

	/**
	 *
	 */

	public $recursive = -1;



	/**
	 *
	 */

	public $validate = [
		'name' => 'isUnique'
	];



	/**
	 *
	 */

	public function buildList( array $tokens ) {

		$data = [ ];

		foreach ( $tokens as $token ) {
			$data[ ] = [ 'name' => $token ];
		}

		$this->saveMany( $data );

		return $this->find( 'list', [
			'conditions' => [
				$this->alias . '.name' => $tokens
			]
		]);
	}
}
