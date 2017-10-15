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
    const DOCTRINE_CACHE_DIRECTORY = 'doctrine';

    const PROXY_DIRECTORY = self::DOCTRINE_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . 'proxy';

    const PROXY_NAMESPACE = 'DoctrineProxies';

    const MODULES_DIRECTORY_PATH = __DIR__ . '/../../Module';

    const ENTITY_DIRECTORY_NAME = 'Model';

    const UUID_TYPE_NAME = 'uuid';

    public function create(
        string $host,
        string $port,
        string $name,
        string $user,
        string $pass,
        string $cacheDir,
        bool $enableCache) : EntityManager
    {
        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'host' => $host,
            'port' => $port,
            'dbname' => $name,
            'user' => $user,
            'password' => $pass,
        );

        $config = $this->createConfig($cacheDir, $enableCache);

        $this->addCustomTypes();

        return EntityManager::create($connectionOptions, $config);
    }

    private function createConfig(string $cacheDir, bool $enableCache): Configuration
    {
        $cacheDir = rtrim($cacheDir, DIRECTORY_SEPARATOR);

        if (!$enableCache) {
            $cache = new ArrayCache();
        } else {
            $cache = new FilesystemCache($cacheDir . DIRECTORY_SEPARATOR . self::DOCTRINE_CACHE_DIRECTORY);
        }

        $config = new Configuration;

        $driverImpl = new AnnotationDriver(new AnnotationReader(), $this->getEntityDirPaths());

        $config->setMetadataDriverImpl($driverImpl);
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($cacheDir . DIRECTORY_SEPARATOR . self::PROXY_DIRECTORY);
        $config->setProxyNamespace(self::PROXY_NAMESPACE);

        if ($enableCache) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }

        return $config;
    }

    private function getEntityDirPaths(): array
    {
        $finder = new Finder();
        $finder->directories()->depth(0)->in(self::MODULES_DIRECTORY_PATH);

        $paths = [];
        foreach ($finder as $dir) {
            $modelDir = $dir->getPathname() . DIRECTORY_SEPARATOR . self::ENTITY_DIRECTORY_NAME;
            if (is_readable($modelDir)) {
                $paths[] = $modelDir;
            }
        }

        return $paths;
    }

    private function addCustomTypes()
    {
        Type::addType(self::UUID_TYPE_NAME, UuidType::class);
    }
}
