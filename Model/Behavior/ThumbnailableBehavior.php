<?php

/**
 *	Provides some additionnal validation methods to be used by a model.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ThumbnailableBehavior extends ModelBehavior {

	/**
	 *	Setup this behavior with the specified configuration settings.
	 *
	 *	### Settings
	 *
	 *	- 'field'
	 *	- 'source' - The type of source from which to grab the original image.
	 *		If it is set to 'url', then the 'field' field should contain the
	 *		URL of the image. If it is set to 'file',
	 *	- 'mandatory' - If mandatory is set to true, the validation will fail
	 *		if the thumbnail couldn't be generated.
	 *	- 'error' - The validation message to set if an error occured.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $config Configuration settings for $model.
	 */

	public function setup( Model $Model, $settings ) {

		$alias = $Model->alias;

		if ( !isset( $this->settings[ $alias ])) {
			$this->settings[ $alias ] = array( );
		}

		$this->settings[ $alias ] = array_merge(
			$this->settings[ $alias ],
			( array )$settings
		);
	}



	/**
	 *
	 */

	public function beforeSave( Model $Model ) {


		return true;
	}
}
