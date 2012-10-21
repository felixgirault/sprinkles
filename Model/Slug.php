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

	public $validate = array(
		'name' => 'isUnique'
	);



	/**
	 *
	 */

	public function buildList( array $tokens ) {

		$data = array( );

		foreach ( $tokens as $token ) {
			$data[ ] = array( 'name' => $token );
		}

		$this->saveMany( $data );

		return $this->find(
			'list',
			array(
				'conditions' => array(
					array(
						$this->alias . '.name' => $tokens
					)
				)
			)
		);
	}
}
