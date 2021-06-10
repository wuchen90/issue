<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public static function getVarDir(): string
    {
        return \sys_get_temp_dir() . '/App/var';
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function getCacheDir(): string
    {
        return self::getVarDir() . '/cache';
    }

    public function getLogDir(): string
    {
        return self::getVarDir() . '/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (\is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (\is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
