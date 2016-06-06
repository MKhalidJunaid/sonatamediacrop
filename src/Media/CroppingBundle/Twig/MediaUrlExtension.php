<?php
/**
 * Created by PhpStorm.
 * User: khalid
 * Date: 11/22/2014
 * Time: 10:32 PM
 */

namespace Media\CroppingBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class MediaUrlExtension extends \Twig_Extension {
	private $container;


	public function __construct( Container $container ) {
		$this->container = $container;
	}
	public function getMediaPublicUrl( $media = null, $format ) {
		if ( $media == null ) {
			return false;
		}
		$provider = $this->container->get( $media->getProviderName() );
		return $provider->generatePublicUrl( $media, $format );
	}
	public function getFunctions() {
		return array(
			'media_public_url' => new \Twig_Function_Method( $this, 'getMediaPublicUrl' ),
		);
	}
	public function getName() {
		return 'traffic_media_url_extension';
	}
}