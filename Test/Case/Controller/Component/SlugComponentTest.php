<?php

App::uses( 'CakeRequest', 'Network' );
App::uses( 'CakeResponse', 'Network' );
App::uses( 'Controller', 'Controller' );
App::uses( 'ComponentCollection', 'Controller' );
App::uses( 'PagematronComponent', 'Controller/Component' );



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
		$this->Controller = new TestPagematronController( $CakeRequest, $CakeResponse );

		$this->Slug->startup( $this->Controller );
	}



	/**
	 *
	 */

	public function testEnsureIntegrity( ) {

		// Test our adjust method with different parameter settings
		$this->Slug->adjust( );
		$this->assertEquals( 20, $this->Controller->paginate['limit']);

		$this->Slug->adjust( 'medium' );
		$this->assertEquals( 50, $this->Controller->paginate['limit']);

		$this->Slug->adjust( 'long' );
		$this->assertEquals( 100, $this->Controller->paginate['limit']);
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