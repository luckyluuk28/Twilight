<?php

declare(strict_types=1);

namespace Twilight\Router;

interface RouterInterface
{
    /** 
    *Add route to routing table
    *
    *@param string $route
    *@param array $params
    *@return void
    */
    public function add(string $route, array $params) : void;

    /**
     * Dispatch route and create controller object and execute the default method
     * on that controller object
     *
     * @param string $url
     * @return void
     */
    public function dispatch(string $url) : void;
}