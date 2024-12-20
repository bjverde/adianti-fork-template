<?php
/**
 * SystemMessageTagForm
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemMessageTagForm extends TWindow
{
    private $form;
    
    /**
     *
     */
    public function __construct($param)
    {
        parent::__construct();
        parent::setSize(600, null);
        parent::setMinWidth(0.9, 600);
        parent::setTitle('Tags');
        
        $id = new THidden('id');
        $tags = new TMultiEntry('tags');
        $tags->setSize('100%', '100');
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setProperty('class', 'card panel noborder');
        $this->form->addFields([$id]);
        $this->form->addFields([new TLabel('Tags')]);
        $this->form->addFields([$tags]);
        
        $btn = $this->form->addAction( _t('Save'), new TAction([$this, 'onSave']), 'fa:check');
        $btn->class = 'btn btn-primary';
        parent::add($this->form);
    }
    
    /**
     *
     */
    public function onEdit($param)
    {
        try
        {
            TTransaction::open('communication');
            $tags = SystemMessageTag::where('system_message_id', '=', $param['id'])->getIndexedArray('tag', 'tag');
            
            $data = new stdClass;
            $data->tags = $tags;
            $data->id = $param['id'];
            $this->form->setData($data);
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage($e->getMessage());
        }
    }
    
    /**
     *
     */
    public function onSave($param)
    {
        try
        {
            TTransaction::open('communication');
            $message = SystemMessage::find($param['id']);
            
            if ($message->system_user_to_id == TSession::getValue('userid'))
            {
                SystemMessageTag::where('system_message_id', '=', $message->id)->delete();
                
                if (!empty($param['tags']))
                {
                    foreach ($param['tags'] as $tag)
                    {
                        $smt = new SystemMessageTag;
                        $smt->system_message_id = $message->id;
                        $smt->tag = $tag;
                        $smt->store();
                    }
                }
            }
            
            TTransaction::close();
            
            AdiantiCoreApplication::loadPage('SystemMessageFormView', 'onView', ['id' => $message->id]);
        }
        catch (Exception $e)
        {
            new TMessage($e->getMessage());
        }
    }
}
