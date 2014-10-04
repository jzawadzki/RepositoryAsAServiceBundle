<?php

/*
 * This file is part of the RegisterAsAService package.
 *
 * (c) Jerzy Zawadzki <zawadzki.jerzy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JZ\RepositoryAsAServiceBundle;

use JZ\RepositoryAsAServiceBundle\Compiler\RegisterRepositoriesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RepositoryAsAServiceBundle extends Bundle
{

    function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterRepositoriesPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
