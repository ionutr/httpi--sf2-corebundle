<?php

namespace Httpi\Bundle\CoreBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;

/**
 * AppDataCollector.
 *
 * @author Vincent Bouzeran <vincent.bouzeran@elao.com>
 */
class AppDataCollector extends DataCollector
{
    private $container;

    /**
     * The Constructor for the App Datacollector
     *
     * @param Container $container    The service container
     * @param boolean   $displayInWdt True if the shortcut should be displayed
     */
    public function __construct(Container $container, $displayInWdt)
    {
        $this->container = $container;
        $this->data['display_in_wdt'] = $displayInWdt;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {

        $appVersion = 'not set';
        if ($this->container->hasParameter('app.version')) {
            $appVersion = $this->container->getParameter('app.version');
        }

        $this->data = array(
            'app' => $appVersion
        );
    }

    public function getApp()
    {
        return $this->data['app'];
    }

    public function getName()
    {
        return 'app';
    }
}