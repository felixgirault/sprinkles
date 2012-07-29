<?php

/**
 *	Checks the integrity of slugs passed in urls
 * 
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@package Sprinkles.Controller.Component
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class SlugComponent extends Component {

	/**
	 *	A reference to the controller that is using the component.
	 *
	 *	@var Controller
	 */

	protected $_Controller = null;



	/**
	 *	Stores a reference to the controller that is using the component.
	 *
	 *	@param Controller $Controller Controller using this component.
	 */

	public function initialize( &$Controller ) {

		$this->_Controller =& $Controller;
	}



	/**
	 *	Checks if the given slugs matches the ones in the given set of results.
	 *	If not, this function triggers a 301 HTTP redirection to the correct url.
	 *	
	 *	Example usage in a controller action :
	 *
	 *	```
	 *		public function view( $id = null, $slug = '' ) {
	 *			$data = $this->Model->findById( $id ):
	 *			
	 *			$this->Slug->ensureIntegrity( $data, array( 'slug' => $slug ));
	 *		}
	 *	```
	 *
	 *	@param array $slug The slugs given in the url.
	 *	@param array $data The set of model results.
	 */

	public function ensureIntegrity( array $slugs, array $data ) {

		$url = Router::parse( $this->_Controller->request->here( ));
		$ok = true;

		foreach ( $slugs as $key => $value ) {
			list( $model, $field ) = pluginSplit( $key );

			if ( $model === null ) {
				$model = $this->_Controller->modelClass;
			}

			if ( $value !== $data[ $model ][ $field ]) {
				$url[ $value ] = $data[ $model ][ $field ];
				$ok = false;
			}
		}

		if ( !$ok ) {
			$this->_Controller->redirect( $this->_cleanUrl( $url ), 301 );
		}
	}



	/**
	 *	Since we can't get directly the current url as an array, let's do the
	 *	dirty ourselves.
	 *
	 *	@param array $url The url to clean.
	 *	@param string The cleaned url.
	 */

	protected function _cleanUrl( $url ) {

		// removes useless passed args

		$reserved = array( 'plugin', 'controller', 'action', 'named', 'pass' );
		$params = $url;

		foreach ( $reserved as $key ) {
			unset( $params[ $key ]);
		}

		for ( $i = 0; $i < count( $params ); $i++ ) {
			array_shift( $url['pass']);
		}

		if ( $url['plugin'] === null ) {
			unset( $url['plugin']);
		}

		// named params

		if ( !empty( $url['named'])) {
			$url = array_merge( $url, $url['named']);
		}

		unset( $url['named']);

		// passed args

		if ( !empty( $url['pass'])) {
			$url = array_merge( $url, $url['pass']);
		}

		unset( $url['pass']);

		// query string

		$query = $this->_Controller->request->query;

		if ( !empty( $query )) {
			$url['?'] = http_build_query( $query );
		}

		return Router::url( $url );
	}
}
