<?php

namespace Krak\SymfonyUtil\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

function createLoader(ContainerBuilder $container, FileLocator $locator = null) {
    $locator = $locator ?: new FileLocator();
    $resolver = new LoaderResolver([
        new XmlFileLoader($container, $locator),
        new YamlFileLoader($container, $locator),
        new IniFileLoader($container, $locator),
        new PhpFileLoader($container, $locator),
        new GlobFileLoader($container, $locator),
        new DirectoryLoader($container, $locator),
        new ClosureLoader($container),
    ]);

    return new DelegatingLoader($resolver);
}

function registerTaggedServiceLocator(ContainerBuilder $container, string $serviceId, string $tagName, ?string $keyAttribute = 'alias', $argumentKey = 0) {
    $container->findDefinition($serviceId)
        ->setArgument($argumentKey, ServiceLocatorTagPass::register(
            $container,
            createServiceReferenceMapFromTaggedIds($container->findTaggedServiceIds($tagName), $keyAttribute)
        ));
}

/** @return Reference[] */
function createServiceReferenceMapFromTaggedIds(array $taggedIds, ?string $keyAttribute = null): array {
    $refMap = [];

    foreach ($taggedIds as $id => $tags) {
        $key = ($keyAttribute ? pickLastAttributeFromTags($keyAttribute, $tags) : null) ?? $id;
        $refMap[$key] = new Reference($id);
    }

    return $refMap;
}

function pickLastAttributeFromTags(string $attribute, array $tags) {
    $lastValue = null;
    foreach ($tags as $tag) {
        if ($tag[$attribute] ?? null) {
            $lastValue = $tag[$attribute];
        }
    }
    return $lastValue;
}

