<?php

/*
 * This file is part of the RegisterAsAService package.
 *
 * (c) Jerzy Zawadzki <zawadzki.jerzy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JZ\RepositoryAsAServiceBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterRepositoriesPass implements CompilerPassInterface
{

    protected function generateServiceName($entity)
    {
        return strtolower(str_replace(Array('Entity\\', '\\'), Array('', '.'), $entity)) . '.repository';
    }

    public function process(ContainerBuilder $container)
    {

        if (!$container->has('doctrine.orm.default_entity_manager')) {
            return;
        }

        $em       = $container->get('doctrine')->getManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metadata as $m) {
            $name           = $this->generateServiceName($m->getName());
            $repositoryName = 'Doctrine\ORM\EntityRepository';
            if ($m->customRepositoryClassName) {
                $repositoryName = $m->customRepositoryClassName;
            }
            if (!$container->has($name)) {

                $definition = new Definition($repositoryName);
                if (method_exists($definition, 'setFactory')) { //compatibility with symfony 3.0
                    $definition->setFactory(Array(new Reference('doctrine.orm.entity_manager'), 'getRepository'));
                } else {
                    $definition
                        ->setFactoryService('doctrine.orm.entity_manager')
                        ->setFactoryMethod('getRepository')
                        ->addArgument($m->getName());
                }
                $container->setDefinition($name, $definition);
            }
        }
    }
} 