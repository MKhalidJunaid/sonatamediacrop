<?php
/**
 * Created by PhpStorm.
 * User: khalid
 * Date: 01/04/2016
 * Time: 10:32 PM
 */

namespace Media\CroppingBundle\Configuration;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class Configuration {
	private $container;
	public function __construct( Container $container ) {
		$this->container = $container;

	}
	public function getShortFQN( $name ) {
		if ( $name == null ) {
			return false;
		}
		$array      = explode( '\\', $name );
		$entityname = $array[ count( $array ) - 1 ];
		unset( $array[ count( $array ) - 1 ] ); /*For entity*/
		unset( $array[ count( $array ) - 1 ] );
		$namespace = join( '', $array );
		return $namespace . ':' . $entityname;
	}
}