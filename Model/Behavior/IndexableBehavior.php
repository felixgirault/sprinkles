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
	 *	- 'tokenFilter' - A function used to filter tokens, defaults
	 *		to IndexableBehavior::filterToken. This function must accept one
	 *		parameter (the original token), and return a filtered token.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $config Configuration settings.
	 */

	public function setup( Model $Model, $settings = [ ]) {

		$alias = $Model->alias;

		if ( !isset( $this->settings[ $alias ])) {
			$this->settings[ $alias ] = [
				'fields' => [
					$Model->displayField => 1
				],
				'tokenFilter' => 'IndexableBehavior::filterToken'
			];
		}

		$this->settings[ $alias ] = array_merge(
			$this->settings[ $alias ],
			( array )$settings
		);
	}



	/**
	 *
	 */

	protected function _bindIndexModel( Model $Model ) {

		if ( !isset( $Model->Index )) {
			$Model->bindModel([
				'hasMany' => [
					'Index' => [
						'className' => 'Sprinkles.Index',
						'foreignKey' => 'model_id',
						'conditions' => [
							'model_name' => $Model->alias
						],
					]
				]
			], false );
		}
	}



	/**
	 *	Indexes data from the model.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param boolean $created Whether the record was created or updated.
	 */

	public function afterSave( Model $Model, $created ) {

		$this->index( $Model, $Model->data );
	}



	/**
	 *	Indexes data from the model.
	 *
	 *	@param Model $Model Model using this behavior.
	 */

	public function index( Model $Model, array $data ) {

		$this->_bindIndexModel( $Model );

		$alias = $Model->alias;
		$id = $data[ $alias ]['id'];
		$weightedTokens = [ ];

		extract( $this->settings[ $alias ]);

		foreach ( $fields as $field => $weight ) {
			if ( empty( $data[ $alias ][ $field ])) {
				continue;
			}

			$tokens = array_filter(
				array_map(
					$tokenFilter,
					$this->tokenize( $data[ $alias ][ $field ])
				)
			);

			foreach ( $tokens as $token ) {
				if ( !isset( $weightedTokens[ $token ])) {
					$weightedTokens[ $token ] = 0;
				}

				$weightedTokens[ $token ] += $weight;
			}
		}

		$list = $this->_tokenList( $Model, array_keys( $weightedTokens ));
		$data = [ ];

		foreach ( $list as $tokenId => $token ) {
			$data[ ] = [
				'token_id' => $tokenId,
				'model_id' => $id,
				'model_name' => $alias,
				'weight' => $weightedTokens[ $token ]
			];
		}

		$Model->Index->deleteAll([
			'model_id' => $id,
			'model_name' => $alias
		]);

		$Model->Index->saveMany( $data );
	}



	/**
	 *
	 */

	protected function _tokenList( Model $Model, array $tokens ) {

		$data = [ ];

		foreach ( $tokens as $token ) {
			$data[ ] = [ 'name' => $token ];
		}

		$Model->Index->Token->saveMany( $data );

		return $Model->Index->Token->find( 'list', [
			'conditions' => [
				$Model->Index->Token->alias . '.name' => $tokens
			]
		];
	}



	/**
	 *	Generates and returns search options from the given query.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param string $query The search query.
	 *	@return array Search options.
	 */

	public function optionsFromQuery( Model $Model, $query ) {

		$this->_bindIndexModel( $Model );

		$tokens = array_map(
			$this->settings[ $Model->alias ]['tokenFilter'],
			$this->tokenize( $query )
		);

		return $this->_optionsFromTokens( $Model, $tokens );
	}



	/**
	 *	Generates and returns search options from the given record.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $data The record data.
	 *	@return array Search options.
	 */

	public function optionsFromRecord( Model $Model, array $data ) {

		$this->_bindIndexModel( $Model );

		$alias = $Model->alias;
		$tokens = [ ];

		extract( $this->settings[ $alias ]);

		foreach ( $fields as $field => $weight ) {
			$tokens += array_map(
				$tokenFilter,
				$this->tokenize( $data[ $alias ][ $field ])
			);
		}

		$options = $this->_optionsFromTokens( $Model, $tokens );
		$options['conditions']["`$alias`.`id` !="] = $data[ $alias ][ $Model->primaryKey ];

		return $options;
	}



	/**
	 *	Generates and returns search options from the given tokens.
	 *
	 *	@param Model $Model Model using this behavior.
	 *	@param array $token The tokens.
	 *	@return array Search options.
	 */

	protected function _optionsFromTokens( Model $Model, array $tokens ) {

		$alias = $Model->alias;
		$indexAlias = $Model->Index->alias;
		$tokenAlias = $Model->Index->Token->alias;
		$db = $Model->getDataSource( );
		$conditions = [ ];

		foreach ( $tokens as $token ) {
			$conditions[ ] = "`$tokenAlias`.`name` LIKE '%$token%'";
		}

		$tokensQuery = $db->buildStatement([
			'fields' => [ "`$tokenAlias`.`id`" ],
			'table' => $db->fullTableName( $Model->Index->Token ),
			'alias' => $tokenAlias,
			'limit' => null,
			'offset' => null,
			'joins' => [ ],
			'conditions' => [ 'OR' => $conditions ],
			'group' => null,
			'order' => null
		], $Model->Index->Token );

		$Model->unbindModel([ 'hasMany' => [ 'Index' ]]);

		return [
			'joins' => [[
				'table' => $Model->Index->table,
				'alias' => $indexAlias,
				'type' => 'INNER',
				'conditions' => [
					"`$indexAlias`.`model_id` = `$alias`.`id`",
					"`$indexAlias`.`model_name`" => $alias,
					"`$indexAlias`.`token_id` IN ( $tokensQuery )"
				]
			]],
			'group' => "`$alias`.`id`",
			'order' => "SUM(`$indexAlias`.`weight`) DESC"
		];
	}



	/**
	 *	Splits a string into multiple tokens.
	 *
	 *	@param string $string The string to extract tokens from.
	 *	@return array The extracted tokens.
	 */

	public function tokenize( $string ) {

		return preg_split( '/[[:punct:][:space:]]+/', $string, -1, PREG_SPLIT_NO_EMPTY );
	}



	/**
	 *	Filters the given token.
	 *
	 *	@param string $tokens The original token.
	 *	@return string The filtered token.
	 */

	public static function filterToken( $token ) {

		return strtolower( Inflector::slug( $token ));
	}
}
