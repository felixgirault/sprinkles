<?php

/**
 *	Extends the capabilities of a Model.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ExtendedBehavior extends ModelBehavior {

	/**
	 *	Returns an array of model's schema fields and their default value, in
	 *	the form `array( 'field' => 'value' )`.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@return array The array of fields.
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
