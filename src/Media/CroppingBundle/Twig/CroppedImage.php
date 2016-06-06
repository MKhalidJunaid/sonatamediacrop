<?php
/**
 * Created by PhpStorm.
 * User: khalid
 * Date: 11/22/2014
 * Time: 10:32 PM
 */

namespace Media\CroppingBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class CroppedImage extends \Twig_Extension {
	private $container;


	public function __construct( Container $container ) {
		$this->container = $container;
	}

	public function getCroppedImage( $media, $size, $object ) {
		if ( $media == null || $size == null ) {
			return false;
		}
		$mediaThumb = '';
		$DM              = $this->container->get( 'Doctrine' )->getManager();
		$mediaThumbsRepo = $DM->getRepository( 'Media\CroppingBundle\Entity\MediaCropping' );
		$configuration   = $this->container->get( 'traffic.parameters.configuration' );
		if(!empty($object)){
			$entityType      = $configuration->getShortFQN( get_class( $object ) );
			$mediaThumb      = $mediaThumbsRepo->findOneBy( array(
					'media'      => $media,
					'entity'     => $object,
					'entityType' => $entityType,
					'sizeKey'    => $size,
				)
			);
		}

		if ( empty( $mediaThumb ) ) {
			$mediaThumb = $mediaThumbsRepo->findOneBy( array(
					'media'   => $media,
					'sizeKey' => $size,
				),
				array( 'updatedAt' => 'desc' )
			);
		}
		if ( ! empty( $mediaThumb ) ) {
			return $mediaThumb;
		} else {
			return false;
		}

	}

	public function getFunctions() {
		return array(
			'getCroppedImage' => new \Twig_Function_Method( $this, 'getCroppedImage' ),
		);
	}

	public function getName() {
		return 'traffic_get_cropped_image_extension';
	}
}