<?php
/**
 * SystemPostViewList
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemWikiPagePicker extends TWindow
{
    public function __construct($param)
    {
        parent::__construct();
        parent::setSize(800,null);
        parent::setTitle(_t('Add wiki link'));
        
        $label = new TEntry('label');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('active', '=', 'Y'));
        
        $page = new TDBCombo('page', 'communication', 'SystemWikiPage', 'id', 'title', 'title', $criteria);
        $page->enableSearch();
        $page->{'class'} = 'form-control';
        $page->setSize('100%');
        
        $form = new BootstrapFormBuilder('wiki_picker');
        $form->enableClientValidation();
        $form->setProperty('class', 'card noborder');
        $form->addFields([new TLabel(_t('Page'))]);
        $form->addFields([$page]);
        $form->addFields([new TLabel(_t('Label'))]);
        $form->addFields([$label]);
        
        $label->addValidation(_t('Label'), new TRequiredValidator);
        $page->addValidation(_t('Page'), new TRequiredValidator);
        
        $form->addAction(_t('Apply'), new TAction([$this, 'onApply']), 'fa:check');
        
        parent::add($form);
    }
    
    /**
     *
     */
    public static function onApply($param)
    {
        $link = 'index.php?class=SystemWikiView&method=onLoad&key='.$param['page'];
        THtmlEditor::insertHTML('wiki_form', 'content', "<a generator='adianti' href='{$link}'>{$param['label']}</a>");
        parent::closeWindow();
    }
}
