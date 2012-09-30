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

	public function buildList( array $tokens ) {

		$query = 'INSERT IGNORE INTO tokens ( name ) '
			. 'VALUES ( \'' . implode( '\' ), ( \'', $tokens ) . '\' );';

		$this->query( $query );

		return $this->find(
			'list',
			array(
				'conditions' => array(
					'name IN( \'' . implode( '\', \'', $tokens ) . '\' )'
				)
			)
		);
	}
}
