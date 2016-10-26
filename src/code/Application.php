<?php

namespace Davamigo\ApacheHome;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The custom app class.
 *
 * @package Davamigo\ApacheHome
 * @author David Amigo <davamigo@gmail.com>
 * @codeCoverageIgnore
 */
class Application extends SilexApplication
{
    use SilexApplication\TwigTrait;

    /**
     * Handles the request and delivers the response.
     *
     * @param Request|null $request Request to process
     */
    public function run(Request $request = null)
    {
        $this->init();

        parent::run($request);
    }

    /**
     * Initializes the app object
     *
     * @return void
     */
    protected function init()
    {
        /** @var Application $app */
        $app = $this;

        $app->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/../views',
        ));

        $app->error(function (\Exception $exc, Request $request, $code) use ($app) {
            return $app->onError($exc, $request, $code);
        });

        $app->get('/', __NAMESPACE__ . '\Controller::index');
    }

    /**
     * Custom error handler
     *
     * @param \Exception $exc
     * @param Request    $request
     * @param int        $code
     * @return Response
     */
    protected function onError(\Exception $exc, Request $request, $code)
    {
        return $this->render('error.html.twig', array(
            'code'      => $code,
            'message'   => $exc->getMessage(),
            'traces'    => $exc->getTrace()
        ));
    }
}
