<?php

namespace Bitter\DropzoneAttribute\Routing;

use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;
use Bitter\DropzoneAttribute\API\V1\Dropzone;
use Bitter\DropzoneAttribute\API\V1\Middleware\FractalNegotiatorMiddleware;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setPrefix('/api/v1')
            ->addMiddleware(FractalNegotiatorMiddleware::class)
            ->routes(function ($groupRouter) {
                /** @var $groupRouter Router */
                $groupRouter->all('/dropzone/upload_file', [Dropzone::class, 'uploadFile']);
                $groupRouter->all('/dropzone/download_files', [Dropzone::class, 'downloadFiles']);
            });
    }
}