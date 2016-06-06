<?php
/**
 * Created by PhpStorm.
 * User: khalid
 * Date: 11/22/2014
 * Time: 10:32 PM
 */

namespace Media\CroppingBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class EntityNameExtension extends \Twig_Extension {
	private $container;


	public function __construct( Container $container ) {
		$this->container = $container;
	}
	public function getShortFQN( $name ) {
		$configuration = $this->container->get('traffic.parameters.configuration' );
		return $configuration->getShortFQN( $name );
	}
	public function getFunctions() {
		return array(
			'getShortFQN' => new \Twig_Function_Method( $this, 'getShortFQN' ),
		);
	}
	public function getName() {
		return 'traffic_entity_name_extension';
	}
}