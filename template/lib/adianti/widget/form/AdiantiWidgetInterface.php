<?php
namespace Adianti\Widget\Form;

/**
 * Widget Interface
 *
 * @version    8.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
interface AdiantiWidgetInterface
{
    public function setName($name);
    public function getName();
    public function setValue($value);
    public function getValue();
    public function show();
}
