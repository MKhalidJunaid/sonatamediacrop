<?php

namespace Media\CroppingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaCroppingBundle extends Bundle
{
	public function getParent()
    {
        return 'ApplicationSonataMediaBundle';
    }
}
