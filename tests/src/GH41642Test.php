<?php

declare(strict_types=1);

namespace App\Tests;

use Composer\InstalledVersions;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class GH41642Test extends KernelTestCase
{
    public function testDenormalization(): void
    {
        $kernel = self::bootKernel();

        $serializer = $kernel->getContainer()->get('serializer');

        $data = [
            'date' => '2021-01-01 00:00:00',
        ];
        $result = $serializer->denormalize($data, Foo::class);

        // Fails here
        self::assertInstanceOf(\DateTimeInterface::class, $result->date);
    }

    public function testWillBeAvailable(): void
    {
        /**
         * $ composer why symfony/framework-bundle
         * wuchen90/issue               dev-main  requires (for development)  symfony/framework-bundle (^5.3)
         * doctrine/mongodb-odm-bundle  4.3.0     requires                    symfony/framework-bundle (^4.3.3|^5.0)
         */
        self::assertTrue(InstalledVersions::isInstalled('doctrine/mongodb-odm-bundle', false));
        self::assertTrue(InstalledVersions::isInstalled('symfony/framework-bundle'));
        self::assertTrue(InstalledVersions::isInstalled('symfony/framework-bundle', false));

        // Fails here
        self::assertTrue(ContainerBuilder::willBeAvailable('phpdocumentor/reflection-docblock', DocBlockFactoryInterface::class, ['symfony/framework-bundle', 'symfony/property-info']));
    }
}

class Foo
{
    /**
     * @var \DateTimeInterface
     */
    public $date;
}
