<?php

/**
 *	Extends the capabilities of a Model.
 *
 *	@package Sprinkles.Model.Behavior
 *	@author Félix Girault <felix.girault@gmail.com>
 */

class ExtendedBehavior extends ModelBehavior {

	/**
	 *	Returns an array of model's schema fields and their default value, in
	 *	the form `array( 'field' => 'value' )`.
	 *
	 *	@param Model $Model model using this behavior
	 *	@return array the array of fields
	 */

	public function defaults( Model $Model ) {

		$schema = $Model->schema( );
		$defaults = array( );

		if ( is_array( $schema )) {
			foreach ( $schema as $field => $meta ) {
				if ( $meta['default'] !== null ) {
					$defaults[ $field ] = $meta['default'];
				}
			}
		}

		return $defaults;
	}
}
