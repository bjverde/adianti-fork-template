<?php
namespace Adianti\Widget\Form;

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Widget\Form\TSelect;
use Adianti\Widget\Base\TScript;

use Exception;

/**
 * MultiCombo Widget
 *
 * @version    8.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
class TMultiCombo extends TSelect implements AdiantiWidgetInterface
{
    protected $id;
    
    /**
     * Class Constructor
     * @param  $name widget's name
     */
    public function __construct($name)
    {
        // executes the parent class constructor
        parent::__construct($name);
        $this->id   = 'tmulticombo_' . mt_rand(1000000000, 1999999999);
        
        $this->tag->{'class'} = 'tmulticombo'; // CSS
        $this->tag->{'widget'} = 'tmulticombo';
        
        parent::setDefaultOption(false);
    }
    
    /**
     * Reload combobox items after it is already shown
     * @param $formname form name (used in gtk version)
     * @param $name field name
     * @param $items array with items
     * @param $startEmpty ...
     */
    public static function reload($formname, $name, $items, $startEmpty = FALSE)
    {
        parent::reload($formname, $name, $items, $startEmpty);
        TScript::create("tmulticombo_reload('{$formname}', '{$name}')", true, 100);
    }
    
    /**
     * Shows the widget
     */
    public function show()
    {
        parent::show();
        
        $labels = [
            'placeholder'     => AdiantiCoreTranslator::translate('Click to search'),
            'search'          => AdiantiCoreTranslator::translate('Search'),
            'searchNoResult'  => AdiantiCoreTranslator::translate('No results'),
            'selectedOptions' => ' ' . AdiantiCoreTranslator::translate('selected'),
            'selectAll'       => AdiantiCoreTranslator::translate('Select all'),
            'unselectAll'     => AdiantiCoreTranslator::translate('Clear the selection'),
            'noneSelected'    => AdiantiCoreTranslator::translate('None selected')
        ];
        $labels_json = json_encode($labels);
        TScript::create("tmulticombo_start('{$this->id}', '{$this->size}', $labels_json);");
    }
}