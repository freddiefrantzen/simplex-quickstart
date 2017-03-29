<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Ramsey\Uuid\Doctrine\UuidType;
use Symfony\Component\Finder\Finder;

class DoctrineOrmFactory
{
    public function create(
        string $host,
        string $port,
        string $name,
        string $user,
        string $pass,
        string $cacheDir,
        bool $debugMode) : EntityManager
    {
        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'host' => $host,
            'port' => $port,
            'dbname' => $name,
            'user' => $user,
            'password' => $pass,
        );

        $config = $this->createConfig($cacheDir, $debugMode);

        $this->addCustomTypes();

        $entityManager = EntityManager::create($connectionOptions, $config);

        return $entityManager;
    }

    private function createConfig(string $cacheDir, bool $debugMode): Configuration
    {
        if ($debugMode) {
            $cache = new ArrayCache();
        } else {
            $cache = new FilesystemCache($cacheDir . 'doctrine/');
        }

        $config = new Configuration;

        $driverImpl = new AnnotationDriver(new AnnotationReader(), $this->getEntityDirPaths());

        $config->setMetadataDriverImpl($driverImpl);
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($cacheDir . 'doctrine/proxy/');
        $config->setProxyNamespace('DoctrineProxies');

        if ($debugMode) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        return $config;
    }

    private function getEntityDirPaths(): array
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in(__DIR__ . '/../../Module');

        $paths = [];
        foreach ($finder as $dir) {
            $modelDir = $dir->getPathname() . '/Model';
            if (is_readable($modelDir)) {
                $paths[] = $modelDir;
            }
        }

        return $paths;
    }

    private function addCustomTypes()
    {
        Type::addType('uuid', UuidType::class);
    }
}
