<?php

namespace Davamigo\ApacheHome;

use Davamigo\ApacheHome\Model\CgiFiles;
use Davamigo\ApacheHome\Model\Files;
use Davamigo\ApacheHome\Model\Ports;
use Davamigo\ApacheHome\Model\ReadConfig;
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
        // Read config file
        $config = new ReadConfig();

        // Scan files and folders
        $files = new Files(
            $config->getDocumentRoot(),
            $config->getIgnoreFiles(),
            $config->getIgnoreFolders()
        );

        // Scan ports
        $ports = new Ports();

        // Scan virtual hosts
        $virtualHosts = new VirtualHosts();

        // Scan CGI files
        $cgis = new CgiFiles(
            $config->getCgiBinPath(),
            $config->getCgiIgnoreFiles(),
            $config->getCgiIgnoreFolders()
        );

        return $app->render('index.html.twig', array(
            'host'      => $config->getHttpHost(),
            'scheme'    => $config->getRequestSchema(),
            'cgifolder' => $config->getCgiBinFolder(),
            'files'     => $files->getFiles(),
            'folders'   => $files->getFolders(),
            'ports'     => $ports->getPorts(),
            'vhosts'    => $virtualHosts->getVirtualHosts(),
            'cgis'      => $cgis->getFilesAndFolders()
        ));
    }
}
