<?php

App::uses( 'HttpSocket', 'Network/Http' );



/**
 *	@package Sprinkles.Model.Datasource
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class OEmbedSource extends DataSource {

	/**
	 *	@see DataSource::$_schema
	 */

	protected $_schema = array(

		// common parameters

		'type' => array(
			'type' => 'string',
			'null' => false
		),
		'version' => array(
			'type' => 'string',
			'null' => true
		),
		'title' => array(
			'type' => 'string',
			'null' => true
		),
		'author_name' => array(
			'type' => 'string',
			'null' => true
		),
		'author_url' => array(
			'type' => 'string',
			'null' => true
		),
		'provider_name' => array(
			'type' => 'string',
			'null' => true
		),
		'provider_url' => array(
			'type' => 'string',
			'null' => true
		),
		'cache_age' => array(
			'type' => 'string',
			'null' => true
		),
		'thumbnail_url' => array(
			'type' => 'string',
			'null' => true
		),
		'thumbnail_width' => array(
			'type' => 'integer',
			'null' => true
		),
		'thumbnail_height' => array(
			'type' => 'integer',
			'null' => true
		),

		// photo type 

		'url' => array(
			'type' => 'string',
			'null' => true
		),

		// video and rich types

		'html' => array(
			'type' => 'text',
			'null' => true
		),

		// photo, video and rich types

		'width' => array(
			'type' => 'integer',
			'null' => true
		),
		'height' => array(
			'type' => 'integer',
			'null' => true
		),

		// not part of the specification, but sometimes provided

		'description' => array(
			'type' => 'text',
			'null' => true
		)
	);


	
	/**
	 *
	 */

	protected $_endpoints = array( );



	/**
	 *	@see DataSource::listSources( )
	 */

	public function listSources( ) {
	   
		return null;
	}



	/**
	 *	@see DataSource::describe( )
	 */

	public function describe( Model $Model ) {
	   
		return $this->_schema;
	}



	/**
	 *	@see DataSource::calculate( )
	 */

	public function calculate( Model $Model, $func, $params = array( )) {
	   
		return 'COUNT';
	}



	/**
	 *	### Conditions
	 *
	 *	- 'url' - The URL to retrieve embedding information for.
	 *	- 'maxwidth' - The maximum width of the embedded resource.
	 *	- 'maxheight' - The maximum height of the embedded resource.
	 *	- 'format' - The required response format.
	 *
	 *	@see DataSource::read( )
	 */

	public function read( Model $Model, $data = array( )) {

		if ( !isset( $this->config['endpoints'])) {
			throw new CakeException( 'OEmbedSource::read( ) : No enpoints available.' );
		}

		$HttpSocket = new HttpSocket( );
		$json = $HttpSocket->get( 'http://example.com/api/list.json', $data['conditions']);
		$data = json_decode( $json, true );

		if ( is_null( $data )) {
			throw new CakeException( json_last_error( ));
		}

		return array( $Model->alias => $data );
	}
}
