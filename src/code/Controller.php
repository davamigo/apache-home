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
        $rootFolder = $_SERVER['DOCUMENT_ROOT'];
        if (!$rootFolder) {
            $rootFolder = __DIR__ . '/../..';
        }

        $cgiPath = 'cgi-bin';
        $cgiFolder = '/var/www/' . $cgiPath . '/';

        $filesToIgnore = array(
            '.gitignore',
            'composer.json',
            'composer.lock',
            'index.php',
            'README.md'
        );

        $foldersToIgnore = array(
            '.',
            '..',
            '.idea',
            '.git',
            'bin',
            'src',
            'vendor'
        );

        $files = new Files(
            $rootFolder,
            $filesToIgnore,
            $foldersToIgnore
        );

        $ports = new Ports();

        $virtualHosts = new VirtualHosts();

        $cgis = new CgiFiles($cgiFolder, array(
            '.',
            '..',
        ));

        $host = (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $scheme = (isset($_SERVER) && isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';

        return $app->render('index.html.twig', array(
            'host'      => $host,
            'scheme'    => $scheme,
            'cgipath'   => $cgiPath,
            'files'     => $files->getFiles(),
            'folders'   => $files->getFolders(),
            'ports'     => $ports->getPorts(),
            'vhosts'    => $virtualHosts->getVirtualHosts(),
            'cgis'      => $cgis->getFiles() + $cgis->getFolders()
        ));
    }
}
