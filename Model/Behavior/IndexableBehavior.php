<?php

/**
 *	Makes a Model indexable.
 *
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Model.Behavior
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class IndexableBehavior extends ModelBehavior {

	/**
	 *	Setup this behavior with the specified configuration settings.
	 *
	 *	### Settings
	 *
	 *	- 'fields' - An array of fields and their respective weights.
	 *	- 'tokenizeCallback' - A function used to generate tokens, defaults
	 *		to IndexableBehavior::tokenize. This function must accept one
	 *		parameter (the original string), and return an array of strings
	 *		(the extracted tokens).
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $config Configuration settings.
	 */

	public function setup( Model $Model, $settings = array( )) {

		$alias = $Model->alias;

		if ( !isset( $this->settings[ $alias ])) {
			$this->settings[ $alias ] = array(
				'fields' => array(
					$Model->displayField => 1
				),
				'tokenizeCallback' => 'IndexableBehavior::tokenize'
			);
		}

		$this->settings[ $alias ] = array_merge(
			$this->settings[ $alias ],
			( array )$settings
		);

		$Model->bindModel(
			array(
				'hasMany' => array(
					'Index' => array(
						'className' => 'Sprinkles.Index',
						'foreignKey' => 'model_id',
						'conditions' => array(
							'model_name' => $alias
						),
					)
				)
			),
			false
		);
	}



	/**
	 *
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param boolean $created Whether the record was created or updated.
	 */

	public function afterSave( Model $Model, $created ) {

		$id = $Model->id;
		$alias = $Model->alias;

		extract( $this->settings[ $alias ]);
		$weightedTokens = array( );

		foreach ( $fields as $field => $weight ) {
			$tokens = call_user_func(
				$tokenizeCallback,
				$Model->data[ $alias ][ $field ]
			);

			foreach ( $tokens as $token ) {
				if ( !isset( $weightedTokens[ $token ])) {
					$weightedTokens[ $token ] = 0;
				}

				$weightedTokens[ $token ] += $weight;
			}
		}

		$list = $Model->Index->Token->buildList( array_keys( $weightedTokens ));
		$data = array( );

		foreach ( $list as $tokenId => $token ) {
			$data[] = array(
				'token_id' => $tokenId,
				'model_id' => $id,
				'model_name' => $alias,
				'weight' => $weightedTokens[ $token ]
			);
		}

		$Model->Index->deleteAll(
			array(
				'model_id' => $id,
				'model_name' => $alias
			)
		);

		$Model->Index->saveMany( $data );
	}



	/**
	 *
	 */

	public function searchOptions( Model $Model, $terms ) {

		$alias = $Model->alias;
		$indexAlias = $Model->Index->alias;
		$tokenAlias = $Model->Index->Token->alias;

		$tokens = call_user_func(
			$this->settings[ $alias ]['tokenizeCallback'],
			$terms
		);

		$db = $Model->getDataSource( );

		$tokensQuery = $db->buildStatement(
			array(
				'fields' => array( "`$tokenAlias`.`id`" ),
				'table' => $db->fullTableName( $Model->Index->Token ),
				'alias' => $tokenAlias,
				'limit' => null,
				'offset' => null,
				'joins' => array( ),
				'conditions' => array(
					"`$tokenAlias`.`name` IN ( '" . implode( "', '", $tokens ) . "' )"
				),
				'group' => null,
				'order' => null
			),
			$Model->Index->Token
		);

		$Model->unbindModel(
			array(
				'hasMany' => array( 'Index' )
			)
		);

		return array(
			'joins' => array(
				array(
					'table' => $Model->Index->table,
					'alias' => $indexAlias,
					'type' => 'INNER',
					'conditions' => array(
						"`$indexAlias`.`model_id` = `$alias`.`id`",
						"`$indexAlias`.`model_name`" => $alias,
						"`$indexAlias`.`token_id` IN ( $tokensQuery )"
					)
				)
			),
			'order' => "`$indexAlias`.`weight` DESC"
		);
	}



	/**
	 *	Splits a string into multiple tokens.
	 *
	 *	@param string $string The string to extract tokens from.
	 *	@return array The extracted tokens.
	 */

	public static function tokenize( $string ) {

		$words = preg_split( '/[[:punct:][:space:]]+/', $string, -1, PREG_SPLIT_NO_EMPTY );
		$tokens = array( );

		foreach ( $words as $word ) {
			$tokens[] = strtolower( Inflector::slug( $word, '-' ));
		}

		return $tokens;
	}
}
