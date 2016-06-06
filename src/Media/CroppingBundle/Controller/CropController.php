<?php

namespace Media\CroppingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Media\CroppingBundle\Entity\MediaCropping;

class CropController extends Controller {

	public function indexAction( $id ) {
		if ( empty( $id ) ) {
			return new JsonResponse( array( 'success' => false, 'message' => 'Media not found' ) );
		}
		$DM        = $this->getDoctrine()->getManager();
		$mediaRepo = $DM->getRepository( 'Application\Sonata\MediaBundle\Entity\Media' );
		$media     = $mediaRepo->find( $id );
		if ( empty( $media ) ) {
			return new JsonResponse( array( 'success' => false, 'message' => 'Media not found' ) );
		}
		$mediaThumbsRepo = $DM->getRepository( 'Media\CroppingBundle\Entity\MediaCropping' );
		$mediaThumbs     = $mediaThumbsRepo->findby( array( 'media' => $media ) );
		$thumbs          = array();
		if ( ! empty( $mediaThumbs ) ) {
			foreach ( $mediaThumbs as $key => $val ) {
				$thumbs[] = array(
					'id'         => $val->getId(),
					'name'       => $val->getName(),
					'path'       => $val->getPath(),
					'meta'       => $val->getMeta(),
					'entityType' => $val->getEntityType(),
					'entity'     => $val->getEntity(),
					'sizeKey'    => $val->getSizeKey(),
					'createdAt'  => $val->getCreatedAt(),
					'updatedAt'  => $val->getUpdatedAt(),
				);
			}
		}
		$response['id']        = $media->getId();
		$response['title']     = $media->getName();
		$provider              = $this->container->get( $media->getProviderName() );
		$response['small']     = $provider->generatePublicUrl( $media, $media->getContext() . '_small' );
		$response['big']       = $provider->generatePublicUrl( $media, $media->getContext() . '_big' );
		$response['reference'] = $provider->generatePublicUrl( $media, 'reference' );
		$response['root_path'] = $this->container->get( 'sonata.media.twig.extension' )->path( $media, 'reference' );
		$response['config']    = $this->container->getParameter( 'media_cropping' );
		$response['sizes']     = $this->getCropSizes();
		$response['thumbs']    = $thumbs;

		return new JsonResponse( array( 'success' => true, 'message' => 'Media found', 'data' => $response ) );
	}

	public function getCropSizes() {
		$config = $this->container->getParameter( 'media_cropping' );
		$sizes  = array();
		if ( ! empty( $config['sizes'] ) ) {
			foreach ( $config['sizes'] as $key => $val ) {
				$sizes[] = array( 'key' => $key, 'width' => $val['width'], 'height' => $val['height'] );
			}
		}

		return $sizes;
	}

	public function saveAction( $id ) {

		if ( empty( $id ) ) {
			return new JsonResponse( array(
					'success' => false,
					'message' => 'Media not found',
					'data'    => '',
					'key'     => ''
				) );
		}
		$DM        = $this->getDoctrine()->getManager();
		$mediaRepo = $DM->getRepository( 'Application\Sonata\MediaBundle\Entity\Media' );
		$media     = $mediaRepo->find( $id );
		if ( empty( $media ) ) {
			return new JsonResponse( array(
					'success' => false,
					'message' => 'Media not found',
					'data'    => '',
					'key'     => ''
				) );
		}
		$mediaThumbsRepo = $DM->getRepository( 'Media\CroppingBundle\Entity\MediaCropping' );
		$mediaThumbs     = $mediaThumbsRepo->findby( array( 'media' => $media ) );
		if ( ! empty( $mediaThumbs ) ) {

		}
		$request  = $this->container->get( 'Request' );
		$response = $this->cropMedia( $request, $media );

		return new JsonResponse( $response );
	}

	public function cropMedia( $request, $media ) {
		$config      = $this->container->getParameter( 'media_cropping' );
		$requestData = $request->attributes->all();
		$data        = $request->query->all();
		$key         = $data['key'];
		$exist       = $data['exist'];
		$x           = $data['x'];
		$y           = $data['y'];
		$w           = $data['w'];
		$h           = (int) $data['h'];
		if ( ! isset( $config['sizes'][ $key ] ) || empty( $config['sizes'][ $key ] ) ) {
			return array( 'success' => false, 'message' => 'Invalid Size/Dimensions', 'data' => '', 'key' => '' );
		}
		$crop_size = $config['sizes'][ $key ];
		$targ_w    = $crop_size['width'];
		$targ_h    = $crop_size['height'];
		//$targ_w = $targ_h = 150;
		$jpeg_quality = $crop_size['quality'];
		$src          = $this->container->get( 'sonata.media.twig.extension' )->path( $media, 'reference' );
		$srcArray     = array_filter( explode( '/', $src ) );
		array_pop( $srcArray );
		$mediaPath       = join( '/', $srcArray );
		$img_r           = imagecreatefromjpeg( $_SERVER['DOCUMENT_ROOT'] . $src );
		$dst_r           = ImageCreateTrueColor( $targ_w, $targ_h );
		$crop_media_name = uniqid() . $key . '-' . $targ_w . 'x' . $targ_h;
		imagecopyresampled( $dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h );
		header( 'Content-type: image/jpeg' );
		imagejpeg( $dst_r, $mediaPath . '/' . $crop_media_name . '.jpeg', $jpeg_quality );
		imagedestroy( $dst_r );
		$DM              = $this->getDoctrine()->getManager();
		$mediaThumbsRepo = $DM->getRepository( 'Media\CroppingBundle\Entity\MediaCropping' );
		$mediaThumb      = $mediaThumbsRepo->findOneBy( array(
				'media'      => $media,
				'entity'     => $requestData['entity'],
				'entityType' => $requestData['entityType'],
				'sizeKey'    => $key,
			)
		);

		if ( empty( $mediaThumb ) || $exist == 0 ) {

			$MediaCropping = new MediaCropping();
			$MediaCropping->setUpdatedAt( new \DateTime( 'now' ) );
			$MediaCropping->setCreatedAt( new \DateTime( 'now' ) );
			$MediaCropping->setName( $crop_media_name . '.jpeg' );
			$MediaCropping->setPath( $mediaPath . '/' . $crop_media_name . '.jpeg' );
			$MediaCropping->setEntity( $requestData['entity'] );
			$MediaCropping->setEntityType( $requestData['entityType'] );
			$MediaCropping->setMedia( $media );
			$MediaCropping->setSizeKey( $key );
			$entity = $DM->getRepository( $requestData['entityType'] )
			             ->find( $requestData['entity'] );
			if ( ! empty( $entity ) ) {
				$MediaCropping->setMeta( $entity->__toString() );
			}
		} elseif ( $exist == 1 ) {
			$MediaCropping = $mediaThumb;
			$entity        = $DM->getRepository( $mediaThumb->getEntityType() )
			                    ->find( $mediaThumb->getEntity() );
			if ( ! empty( $entity ) ) {
				$MediaCropping->setMeta( $entity->__toString() );
			}
			$MediaCropping->setUpdatedAt( new \DateTime( 'now' ) );
			$MediaCropping->setPath( $mediaPath . '/' . $crop_media_name . '.jpeg' );
			$MediaCropping->setName( $crop_media_name . '.jpeg' );
		}

		$validator = $this->get( 'validator' );
		$errors    = $validator->validate( $MediaCropping );
		if ( count( $errors ) > 0 ) {
			$errorsString = array();
			foreach ( $errors as $key => $val ) {
				$errorsString[] = $val->getMessage();
			}
			$errorsString = '<ul><li>' . join( '</li><li>', $errorsString ) . '</li></ul>';

			return array(
				'success' => false,
				'message' => 'Media Already Exist',
				'data'    => $errorsString,
				'key'     => 'exist'
			);
		}
		$DM->persist( $MediaCropping );
		$DM->flush();

		return array( 'success' => true, 'message' => 'Media Created', 'data' => '', 'key' => '' );
	}
}
