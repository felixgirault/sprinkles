<?php

App::uses( 'CakeRequest', 'Network' );
App::uses( 'CakeResponse', 'Network' );
App::uses( 'Controller', 'Controller' );
App::uses( 'ComponentCollection', 'Controller' );
App::uses( 'SlugComponent', 'Sprinkles.Controller/Component' );



/**
 *	Fake controller to test against.
 */

class TestSlugController extends Controller {
	
	/**
	 *
	 */

	public $paginate = null;
}



/**
 *
 */

class SlugComponentTest extends CakeTestCase {

	/**
	 *
	 */

	public $Slug = null;
	


	/**
	 *
	 */

	public $Controller = null;



	/**
	 *
	 */

	public function setUp( ) {

		parent::setUp( );

		$Collection = new ComponentCollection( );
		$CakeRequest = new CakeRequest( );
		$CakeResponse = new CakeResponse( );

		$this->Slug = new SlugComponent( $Collection );
		$this->Controller = new TestSlugController( $CakeRequest, $CakeResponse );

		$this->Slug->startup( $this->Controller );
	}



	/**
	 *
	 */

	public function testEnsureIntegrity( ) {

		$article = $this->Controller->Article->findById( 1 );

		$this->Slug->ensureIntegrity( array( 'slug' => $article['Article']['slug']), $article );
	}



	/**
	 *
	 */

	public function tearDown( ) {

		parent::tearDown( );

		unset( $this->Slug );
		unset( $this->Controller );
	}
}