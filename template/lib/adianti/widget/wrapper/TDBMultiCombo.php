<?php
namespace Adianti\Widget\Wrapper;

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TCriteria;
use Adianti\Widget\Base\TScript;

use Exception;

/**
 * DBMultiCombo Widget
 *
 * @version    8.0
 * @package    widget
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license
 */
class TDBMultiCombo extends TDBSelect
{
    protected $id;
    
    /**
     * Class Constructor
     * @param  $name widget's name
     */
    public function __construct($name, $database, $model, $key, $value, $ordercolumn = NULL, ?TCriteria $criteria = NULL)
    {
        // executes the parent class constructor
        parent::__construct($name, $database, $model, $key, $value, $ordercolumn, $criteria);
        
        $this->id   = 'tdbmulticombo_' . mt_rand(1000000000, 1999999999);
        
        $this->tag->{'class'} = 'tdbmulticombo'; // CSS
        $this->tag->{'widget'} = 'tdbmulticombo';
        
        parent::setDefaultOption(false);
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