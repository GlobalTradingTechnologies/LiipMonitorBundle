<?php

namespace Liip\MonitorBundle\Helper;

use Symfony\Bundle\FrameworkBundle\Templating\Helper\AssetsHelper;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PathHelper
{
    protected $assetsHelper;
    protected $routerHelper;

    /**
     * Constructor
     *
     * @param AssetsHelper $assetsHelper
     * @param RouterHelper $routerHelper
     */
    public function __construct(AssetsHelper $assetsHelper, RouterHelper $routerHelper)
    {
        $this->assetsHelper = $assetsHelper;
        $this->routerHelper = $routerHelper;
    }

    /**
     * @param array $routes
     * @return array
     */
    public function generateRoutes(array $routes)
    {
        $ret = array();
        foreach ($routes as $route => $params) {
            // path method is available only since 2.8
            $ret[] = sprintf('api.%s = "%s";', $route, $this->routerHelper->path($route, $params));
        }

        return $ret;
    }

    /**
     * @param array $routes
     * @return string
     */
    public function getRoutesJs(array $routes)
    {
        $script = '<script type="text/javascript" charset="utf-8">';
        $script .= "var api = {};\n";
        $script .= implode("\n", $this->generateRoutes($routes));
        $script .= '</script>';

        return $script;
    }

    /**
     * @param array $paths
     * @return string
     */
    public function getScriptTags(array $paths)
    {
        $ret = '';
        foreach ($paths as $path) {
            $ret .= sprintf('<script type="text/javascript" charset="utf-8" src="%s"></script>%s', $this->assetsHelper->getUrl($path), "\n");
        }

        return $ret;
    }

    /**
     * @param array $paths
     * @return string
     */
    public function getStyleTags(array $paths)
    {
        $ret = '';
        foreach ($paths as $path) {
            $ret .= sprintf('<link rel="stylesheet" href="%s" type="text/css">%s', $this->assetsHelper->getUrl($path), "\n");
        }

        return $ret;
    }
}