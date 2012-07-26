<?php

/**
 *	Provides some new validation methods that can be used by a model.
 *
 *	@package Sprinkles.Model.Behavior
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ValidatableBehavior extends ModelBehavior {

	/**
	 *	Checks if two fields are equal.	
	 *
	 *	@param Model $Model model using this behavior
	 *	@param array $check must be passed as: array( 'field' => 'fieldName');
	 *	@param string $otherField the other field name to compare
	 */

	public function sameAs( Model $Model, $check, $otherField ) {

		$keys = array_keys( $check );
		$field = array_shift( $keys );

		return ( $this->data[ $this->alias ][ $field ] == $this->data[ $this->alias ][ $otherField ]);
	}
}
