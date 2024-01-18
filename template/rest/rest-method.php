<?php
require_once 'request.php';

try
{
    $location = 'http://git/template/rest.php?class=SystemUserCliService&method=create';
    $body = [];
    $body['login']      = 'peter';
    $body['name']       = 'peter';
    $body['password']   = '123';
    $body['active']     = '1';
    
    print_r( request($location, 'POST', $body, 'Basic 123') );
}
catch (Exception $e)
{
    echo 'Error: '. $e->getMessage();
}
