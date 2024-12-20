<?php
/**
 * CommonPage
 *
 * @version    8.0
 * @package    control
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class CommonPage extends TPage
{
    public function __construct()
    {
        parent::__construct();
        parent::add(new TLabel('Common page'));
    }
}
