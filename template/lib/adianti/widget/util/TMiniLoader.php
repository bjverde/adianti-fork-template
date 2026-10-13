<?php
namespace Adianti\Widget\Util;

use Adianti\Widget\Base\TElement;

/**
 * TMiniLoader
 *
 * @version    8.7
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
class TMiniLoader extends TElement
{
    /**
     * Class Constructor
     */
    public function __construct($size = '12', $color = 'var(--bs-secondary)')
    {
        parent::__construct('div');
        
        $this->{'id'} = 'tminiloader';
        $this->{'class'} = 'tminiloader';
        
        $wrapper = new TElement('div');
        $wrapper->{'class'} = 'd-flex';
        
        $dot_style = '';
        if (!empty($size))
        {
            $dot_style .= "width:{$size}px;height:{$size}px;";
        }
        if (!empty($color))
        {
            $dot_style .= "background-color:{$color}";
        }
        
        $dot1 = TElement::tag('div', '', ['class' => 'dot', 'style' => $dot_style]);
        $dot2 = TElement::tag('div', '', ['class' => 'dot', 'style' => $dot_style]);
        $dot3 = TElement::tag('div', '', ['class' => 'dot', 'style' => $dot_style]);
        
        $wrapper->add($dot1);
        $wrapper->add($dot2);
        $wrapper->add($dot3);
        
        parent::add($wrapper);
    }
}
