<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Gedmo\DoctrineExtensions as GedmoExtensions;
use Gedmo\Timestampable\TimestampableListener;
use Ramsey\Uuid\Doctrine\UuidType;
use Simplex\Quickstart\Shared\Doctrine\Type\DateTimeMicroType;
use Symfony\Component\Finder\Finder;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DoctrineOrmFactory
{
    const DOCTRINE_CACHE_DIRECTORY = 'doctrine';

    const PROXY_DIRECTORY = self::DOCTRINE_CACHE_DIRECTORY . DIRECTORY_SEPARATOR . 'proxy';

    const PROXY_NAMESPACE = 'DoctrineProxies';

    const MODULES_DIRECTORY_PATH = __DIR__ . '/../../Module';

    const ENTITY_DIRECTORY_NAME = 'Model';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function create(
        string $host,
        string $port,
        string $name,
        string $user,
        string $pass,
        string $cacheDir,
        bool $enableCache
    ): EntityManager {

        $connectionOptions = [
            'driver' => 'pdo_mysql',
            'host' => $host,
            'port' => $port,
            'dbname' => $name,
            'user' => $user,
            'password' => $pass,
        ];

        $annotationReader = new AnnotationReader();

        $config = $this->buildConfig($cacheDir, $enableCache, $annotationReader);

        $this->addCustomTypes();

        $entityManager = $this->buildEntityManager($annotationReader, $connectionOptions, $config);

        return $entityManager;
    }

    private function buildConfig(string $cacheDir, bool $enableCache, AnnotationReader $annotationReader): Configuration
    {
        $config = new Configuration;

        $this->configureMetadataDrivers($annotationReader, $config);

        $this->configureCache($cacheDir, $enableCache, $config);

        $this->configureNamingStrategy($config);

        return $config;
    }

    private function configureMetadataDrivers(AnnotationReader $annotationReader, Configuration $config): void
    {
        $driverChain = new MappingDriverChain();

        $defaultDriver = new AnnotationDriver($annotationReader, $this->getEntityDirPaths());

        $driverChain->setDefaultDriver($defaultDriver);
        $config->setMetadataDriverImpl($driverChain); // was driver imp

        GedmoExtensions::registerAbstractMappingIntoDriverChainORM(
            $driverChain,
            $annotationReader
        );
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

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private function configureCache(string $cacheDir, bool $enableCache, Configuration $config): void
    {
        $cacheDir = rtrim($cacheDir, DIRECTORY_SEPARATOR);

        if (!$enableCache) {
            $cache = new ArrayCache();
        } else {
            $cache = new FilesystemCache($cacheDir . DIRECTORY_SEPARATOR . self::DOCTRINE_CACHE_DIRECTORY);
        }

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($cacheDir . DIRECTORY_SEPARATOR . self::PROXY_DIRECTORY);
        $config->setProxyNamespace(self::PROXY_NAMESPACE);

        if ($enableCache) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }
    }

    private function configureNamingStrategy(Configuration $config): void
    {
        $namingStrategy = new UnderscoreNamingStrategy(CASE_LOWER);
        $config->setNamingStrategy($namingStrategy);
    }

    private function addCustomTypes(): void
    {
        Type::addType(UuidType::NAME, UuidType::class);
        Type::addType(DateTimeMicroType::NAME, DateTimeMicroType::class);
    }

    private function buildEntityManager(
        AnnotationReader $annotationReader,
        array $connectionOptions,
        Configuration$config
    ): EntityManager {

        $eventManager = $this->buildEventManager($annotationReader);

        $entityManager = EntityManager::create($connectionOptions, $config, $eventManager);

        return $entityManager;
    }

    private function buildEventManager(AnnotationReader $annotationReader): EventManager
    {
        $eventManager = new EventManager();

        $this->addEventListeners($annotationReader, $eventManager);

        return $eventManager;
    }

    private function addEventListeners(AnnotationReader $annotationReader, EventManager $eventManager): void
    {
        $timestampableListener = new TimestampableListener();
        $timestampableListener->setAnnotationReader($annotationReader);

        $eventManager->addEventSubscriber($timestampableListener);
    }
}
