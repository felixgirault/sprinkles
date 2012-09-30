<?php

/**
 *	Provides some additionnal validation methods to be used by a model.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ValidatableBehavior extends ModelBehavior {

	/**
	 *	Checks if two fields are equal.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $check Must be passed as `array( 'field' => 'fieldName')`
	 *	@param string $otherField The other field name to compare
	 */

	public function sameAs( Model $Model, $check, $otherField ) {

		$keys = array_keys( $check );
		$field = array_shift( $keys );

		return ( $this->data[ $this->alias ][ $field ] == $this->data[ $this->alias ][ $otherField ]);
	}
}
