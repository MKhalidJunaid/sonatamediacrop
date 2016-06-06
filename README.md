# Sonata-Media-Crop
<<<<<<< HEAD
This Bundle user jcrop library for image cropping and needs FOS Js Routing bundle for routes in js file
=======
This Bundle needs FOS Js Routing bundle for routes in js file
>>>>>>> 6d8d466d7a8b19f2916c4256728e86d1550d3d7e

Import configuration for this bundle in `config.yml`

	imports:
	    - { resource: @MediaCroppingBundle/Resources/config/twig.yml }
	    - { resource: @MediaCroppingBundle/Resources/config/media_cropping.yml }

Import routing for these bundles in main `routing.yml`

	media_cropping:
	    resource: "@MediaCroppingBundle/Resources/config/routing.yml"
	    prefix:   /

	fos_js_routing_js:
	    resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.xml"

Enable bundle in `AppKernel.php`

	new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
	new Media\CroppingBundle\MediaCroppingBundle(),

Usage

You can configure sizes under `media_cropping` node in `media_cropping.yml` that you want to use for cropping media

    media_cropping:
        sizes:
            icon:   { width: 55 , height: 55 , quality: 90}
            small:  { width: 100 , height: 100 , quality: 90}
            banner: { width: 1366 , height: 320 ,quality: 90}
