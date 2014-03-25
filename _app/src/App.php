<?php

use Application\DependencyInjection\Compiler\MergeExtensionsPass;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernel;

class App
{

    /**
     * @var ContainerInterface
     */
    protected static $container;

    public function __construct($environment, $debug = true)
    {
        Debug::enable();
        
        static::$container = self::createContainer($environment, $debug);
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
        return static::get('twig')->render($view, $params);
    }

    protected function createContainer($environment, $debug)
    {
        $rootDir = $this->getRootDir();
        $file = $rootDir . '/cache/container_' . $environment . '.php';
        $containerClass = 'Container' . ucfirst($environment);
        $containerConfigCache = new ConfigCache($file, $debug);

        if (!$containerConfigCache->isFresh()) { //si no está actualizado
            $containerBuilder = new ContainerBuilder();

            $config = require $rootDir . '/configuration.php';

            foreach ($config['extensions'] as $extension) {
                $containerBuilder->registerExtension($extension); //registramos las extensiones en el container
            }

            foreach ($config['compilers'] as $compiler) {
                $containerBuilder->addCompilerPass($compiler); //registramos los compilers en el container
            }

            $containerBuilder->getCompiler()
                    ->getPassConfig()
                    ->setMergePass(new MergeExtensionsPass());

            $loader = new YamlFileLoader($containerBuilder, new FileLocator($rootDir . '/config/'));
            $loader->load('config.yml');

            $containerBuilder->compile();

            $dumper = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                    $dumper->dump(array(
                        'class' => $containerClass,
                    )), $containerBuilder->getResources()
            );
        }

        require_once $file;

        return new $containerClass();
    }

    public function getRootDir()
    {
        return dirname(__DIR__);
    }

}
