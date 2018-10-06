<?php

namespace DomingoLlanes\StrongParametersBundle;

use DomingoLlanes\StrongParametersBundle\DependencyInjection\StrongParametersExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StrongParametersBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new StrongParametersExtension();
        }
        return $this->extension;
    }
}
