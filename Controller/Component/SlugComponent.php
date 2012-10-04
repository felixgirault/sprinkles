<?php

App::uses( 'Sprinkles', 'Sprinkles.Lib' );



/**
 *	Checks the integrity of slugs passed in urls.
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

	public function initialize( Controller $Controller ) {

		$this->_Controller = $Controller;
	}



	/**
	 *	Checks if the given slugs matches the ones in the given set of results.
	 *	If not, this function triggers a 301 HTTP redirection to the correct url.
	 *
	 *	Example usage in a controller action :
	 *
	 *	```
	 *		public function view( $id = null, $slug = '' ) {
	 *
	 *			$data = $this->Model->findById( $id ):
	 *			$this->Slug->ensureIntegrity( $data, array( 'slug' => $slug ));
	 *
	 *			// or simply
	 *			$this->Slug->ensureIntegrity( $data, compact( 'slug' ));
	 *		}
	 *	```
	 *
	 *	@param array $data The set of model results.
	 *	@param array $slug The slugs given in the url.
	 */

	public function ensureIntegrity( array $data, array $slugs ) {

		$url = Sprinkles::url( $this->_Controller->request );
		$ok = true;

		foreach ( $slugs as $key => $value ) {
			list( $alias, $field ) = pluginSplit( $key );

			if ( $alias === null ) {
				$alias = $this->_Controller->modelClass;
			}

			if ( isset( $data[ $alias ][ $field ])) {
				if ( $value !== $data[ $alias ][ $field ]) {
					$url[ $field ] = $data[ $alias ][ $field ];
					$ok = false;
				}
			}
		}

		if ( !$ok ) {
			$this->_Controller->redirect( $url, 301 );
		}
	}
}
