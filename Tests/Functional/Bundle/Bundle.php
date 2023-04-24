<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional\Bundle;

use Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional\Bundle\DependencyInjection\BundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class Bundle extends BaseBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BundleExtension();
    }
}
