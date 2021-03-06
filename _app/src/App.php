<?php

use Application\DependencyInjection\Compiler\MergeExtensionsPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

class App
{

    /**
     * @var ContainerInterface
     */
    protected static $container;
    protected $debug;
    protected $environment;

    public function __construct($environment, $debug = true)
    {
        Debug::enable();

        static::$container = self::createContainer($environment, $debug);
        $this->environment = $environment;
        $this->debug = $debug;
    }

    public function run(Request $request)
    {

        $kernel = new HttpKernel(static::get('event_dispatcher')
                , static::get('controller_resolver'));

        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }

    public static function get($service)
    {
        return static::$container->get($service);
    }

    public static function getparameter($parameter)
    {
        return static::$container->getParameter($parameter);
    }

    public static function render($view, array $params = array())
    {
        $content = static::get('twig')->render($view, $params);
        
        return new Response($content);
    }

    protected function createContainer($environment, $debug)
    {
        $rootDir = $this->getRootDir();
        $file = $rootDir . '/_app/cache/classes/container_' . $environment . '.php';
        $containerClass = 'Container' . ucfirst($environment);
        $containerConfigCache = new ConfigCache($file, $debug);

        if (!$containerConfigCache->isFresh()) { //si no está actualizado
            $containerBuilder = new ContainerBuilder(new ParameterBag(array(
                'root_dir' => $rootDir,
                'debug' => $debug,
                'environment' => $environment,
                'cache_dir' => $rootDir . '/_app/cache/',
            )));

            $this->prepareExtensions($this, $containerBuilder);

            $loader = new YamlFileLoader($containerBuilder, new FileLocator($rootDir . '/_app/config/'));
            $loader->load('config.yml');

            $containerBuilder->compile();

            $this->dumpContainer($containerConfigCache, $containerBuilder, $containerClass);
        }

        require_once $file;

        return new $containerClass();
    }

    public function getRootDir()
    {
        return dirname(dirname(__DIR__));
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    protected function prepareExtensions(App $app, ContainerBuilder $containerBuilder)
    {
        $config = require $this->getRootDir() . '/_app/configuration.php';

        foreach ($config['extensions'] as $extension) {
            //registramos las extensiones en el container
            $containerBuilder->registerExtension($extension);
        }

        foreach ($config['compilers'] as $compiler) {
            //registramos los compilers en el container
            $containerBuilder->addCompilerPass($compiler);
        }

        $containerBuilder->getCompiler()
                ->getPassConfig()
                ->setMergePass(new MergeExtensionsPass());
    }

    protected function dumpContainer(ConfigCache $cache, ContainerBuilder $containerBuilder, $containerClass)
    {
        $dumper = new PhpDumper($containerBuilder);
        $cache->write(
                $dumper->dump(array(
                    'class' => $containerClass,
                )), $containerBuilder->getResources()
        );
    }

}
