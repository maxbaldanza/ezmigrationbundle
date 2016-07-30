<?php

namespace Kaliop\eZMigrationBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaggedServicesCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has('ez_migration_bundle.migration_service')) {
            $migrationService = $container->findDefinition('ez_migration_bundle.migration_service');

            $definitionHandlers = $container->findTaggedServiceIds('ez_migration_bundle.definition_handler');
            foreach ($definitionHandlers as $id => $tags) {
                $migrationService->addMethodCall('addDefinitionHandler', array(
                    new Reference($id)
                ));
            }

            $executors = $container->findTaggedServiceIds('ez_migration_bundle.executor');
            foreach ($executors as $id => $tags) {
                $migrationService->addMethodCall('addExecutor', array(
                    new Reference($id)
                ));
            }
        }



        if ($container->has('ez_migration_bundle.handler.location_resolver')) {
            $locationResolverHandler = $container->findDefinition('ez_migration_bundle.handler.location_resolver');
            $locationResolvers = $container->findTaggedServiceIds('ez_migration_bundle.location_resolver');

            foreach ($locationResolvers as $id => $tags) {
                $locationResolverHandler->addMethodCall('addResolver', array(
                    new Reference($id)
                ));
            }
        }
    }
}