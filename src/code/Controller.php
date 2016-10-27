<?php

namespace Davamigo\ApacheHome;

use Davamigo\ApacheHome\Model\CgiFiles;
use Davamigo\ApacheHome\Model\Files;
use Davamigo\ApacheHome\Model\Ports;
use Davamigo\ApacheHome\Model\VirtualHosts;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The main controller
 *
 * @package Davamigo\ApacheHome
 * @author David Amigo <davamigo@gmail.com>
 * @codeCoverageIgnore
 */
class Controller
{
    /**
     * @param Request     $request
     * @param Application $app
     * @return Response
     */
    public function index(Request $request, Application $app)
    {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        if (!$documentRoot) {
            $documentRoot = __DIR__ . '/../..';
        }

        $cgiFolder = 'cgi-bin';
        $cgiPath = '/var/www/' . $cgiFolder . '/';

        $filesToIgnore = array();

        $foldersToIgnore = array(
            '.',
            '..'
        );

        $files = new Files(
            $documentRoot,
            $filesToIgnore,
            $foldersToIgnore
        );

        $ports = new Ports();

        $virtualHosts = new VirtualHosts();

        $cgis = new CgiFiles($cgiPath, array(
            '.',
            '..',
        ));

        $host = (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $scheme = (isset($_SERVER) && isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';

        return $app->render('index.html.twig', array(
            'host'      => $host,
            'scheme'    => $scheme,
            'cgifolder' => $cgiFolder,
            'files'     => $files->getFiles(),
            'folders'   => $files->getFolders(),
            'ports'     => $ports->getPorts(),
            'vhosts'    => $virtualHosts->getVirtualHosts(),
            'cgis'      => $cgis->getFilesAndFolders()
        ));
    }
}
