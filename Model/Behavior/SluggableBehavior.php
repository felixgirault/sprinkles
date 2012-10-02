<?php

/**
 *	Generate slugs from model fields, either on the fly, either to store them
 *	in database.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class SluggableBehavior extends ModelBehavior {

	/**
	 *	Setup this behavior with the specified configuration settings.
	 *
	 *	### Settings
	 *
	 *	- 'field' - The field which the slug will be generated from,
	 *		defaults to the model's displayField.
	 *	- 'slug' - The field which the slug will be stored in, defaults
	 *		to 'slug'.
	 *	- 'slugCallback' - A function used to generate a slug, defaults
	 *		to SluggableBehavior::slug. This function must accept one
	 *		parameter (the original string), and return a string (the
	 *		slug).
	 *	- 'persistent' - Whether the field should be stored in the database
	 *		or generated on the fly when retrieving records.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $config Configuration settings.
	 */

	public function setup( Model $Model, $settings = array( )) {

		$alias = $Model->alias;

		if ( !isset( $this->settings[ $alias ])) {
			$this->settings[ $alias ] = array(
				'field' => $Model->displayField,
				'slug' => 'slug',
				'slugCallback' => 'SluggableBehavior::slug',
				'persistent' => true
			);
		}

		$this->settings[ $alias ] = array_merge(
			$this->settings[ $alias ],
			( array )$settings
		);
	}



	/**
	 *	Generates a slug to be stored in database.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@return mixed False if the operation should abort.
	 */

	public function beforeSave( Model $Model ) {

		$alias = $Model->alias;

		if ( isset( $this->settings[ $alias ])) {
			extract( $this->settings[ $alias ]);

			if ( $persistent && isset( $Model->data[ $alias ][ $field ])) {
				$Model->data[ $alias ][ $slug ] = call_user_func(
					$slugCallback,
					$Model->data[ $alias ][ $field ]
				);
			}
		}

		return true;
	}



	/**
	 *	Generates a slug on the fly.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param mixed $results The results of the find operation.
	 *	@param boolean $primary Whether this model is being queried directly
	 *		(vs. being queried as an association).
	 *	@return mixed An array value will replace the value of $results -
	 *		any other value will be ignored.
	 */

	public function afterFind( Model $Model, $results, $primary ) {

		$alias = $Model->alias;

		if ( isset( $this->settings[ $alias ])) {
			extract( $this->settings[ $alias ]);

			if ( !$persistent ) {
				foreach ( $results as $result ) {
					if ( isset( $result[ $alias ][ $field ])) {
						$result[ $alias ][ $slug ] = call_user_func(
							$slugCallback,
							$result[ $alias ][ $field ]
						);
					}
				}
			}
		}

		return $results;
	}



	/**
	 *	Generates a slug from the given string.
	 *
	 *	@param string $string The string to generate a slug from.
	 *	@param string The generated slug.
	 */

	public static function slug( $string ) {

		return strtolower( Inflector::slug( $string, '-' ));
	}
}
