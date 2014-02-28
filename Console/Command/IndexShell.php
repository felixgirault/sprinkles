<?php

/**
 *
 */

class IndexShell extends AppShell {

	/**
	 *
	 */

	public function getOptionParser( ) {

		$parser = parent::getOptionParser( );

		$parser->addOption( 'model', [
			'help' => __( 'Model to index.' ),
			'short' => 'm',
			'required' => true
		]);

		$parser->addOption( 'start', [
			'short' => 's',
			'default' => 1
		]);

		$parser->addOption( 'block', [
			'short' => 'b',
			'default' => 1000
		]);

		return $parser;
	}



	/**
	 *
	 */

	public function main( ) {

		$modelName = $this->params['model'];

		$this->uses = [ $modelName ];
		$this->_loadModels( );

		if ( !isset( $this->{$modelName})) {
			$this->out( '<error>' . __( 'Unable to load model' ) . '</error>' );
			return;
		}

		$records = $this->{$modelName}->find( 'all', [
			'offset' => $this->params['start'],
			'limit' => $this->params['block'],
			'order' => "$modelName.id"
		]);

		if ( empty( $records )) {
			$this->out( '<error>' . __( 'No records found' ) . '</error>' );
			return;
		}

		foreach ( $records as $record ) {
			$this->{$modelName}->index( $record );
		}
	}
}
