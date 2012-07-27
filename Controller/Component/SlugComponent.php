<?php

/**
 *	Checks the integrity of slugs passed in urls
 *
 *	@package Sprinkles.Controller.Component
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class SlugComponent extends Component {

	/**
	 *	Params from the sets of results to use in the redirection.
	 *
	 *	@var array
	 */

	public $params = array( 'id', 'slug' );



	/**
	 *	A reference to the controller that is using the component.
	 *
	 *	@var Controller
	 */

	protected $_Controller = null;



	/**
	 *	Constructor.
	 *
	 *	@param ComponentCollection $Collection A ComponentCollection this
	 *		component can use to lazy load its components.
	 *	@param array $settings Array of configuration settings.
	 */

	public function __construct( ComponentCollection $Collection, array $settings = array( )) {

		parent::__construct( $Collection, $settings );

		foreach ( $this->params as $key => $value ) {
			if ( is_numeric( $key )) {
				$this->params[ $value ] = array( );
				unset( $this->params[ $key ]);
			}
		}
	}



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
	 *	@param array $data The set of model results.
	 *	@param array $slug The slugs given in the url.
	 */

	public function ensureIntegrity( array $data, array $slugs ) {

		$valid = true;
		$url = Router::parse( $this->_Controller->request->here( ));

		foreach ( $this->params as $key => $params ) {
			$alias = isset( $params['alias'])
				? $params['alias']
				: $this->_Controller->modelClass;
				
			$field = isset( $params['field'])
				? $params['field']
				: $key;

			if ( isset( $slugs[ $key ])) {
				if ( $slugs[ $key ] !== $data[ $alias ][ $field ]) {
					$valid = false;
				}
			}

			$url[ $key ] = $data[ $alias ][ $field ];
		}

		if ( !$valid ) {
			$this->_Controller->redirect( $url, 301 );
		}
	}
}
