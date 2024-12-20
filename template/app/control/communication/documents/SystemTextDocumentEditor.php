<?php
/**
 * SystemTextDocumentEditor
 *
 * @version    8.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemTextDocumentEditor extends TPage
{
    public function __construct($param)
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        $this->form = new BootstrapFormBuilder('form_text_editor');
        $this->form->setFormTitle(_t('File'));
        
        $id = new THidden('id');
        $this->form->addFields([$id]);
        
        $btn = $this->form->addAction( _t('Save'), new TAction([$this, 'onSave']), 'fa:check');
        $btn->{'class'} = 'btn btn-primary';
        
        $this->form->setColumnClasses(2, ['col-sm-3', 'col-sm-9']);
        $this->form->addHeaderActionLink( _t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
        parent::add($this->form);
    }
    
    /**
     *
     */
    public function onEdit($param)
    {
        $id = (int) str_replace('system_document_', '', $param['id']);
        
        try
        {
            TTransaction::open('communication');
            $doc = SystemDocument::find($id);
            $doc->checkPermission();
            
            $obj = new stdClass;
            $obj->id  = $id;
            
            if ($doc->content_type == 'html')
            {
                $text = new THtmlEditor('text');
                $text->setSize('100%', '700');
                $this->form->addFields([$text]);
            }
            else
            {
                $text = new TText('text');
                $text->style = 'font-family: "Droid Sans Mono", "monospace", monospace;';
                $text->setSize('100%', '800');
                $this->form->addFields([$text]);
            }
            if ($doc->filename)
            {
                $this->form->setFormTitle(basename($doc->filename));
                $obj->text = file_get_contents("files/system/documents/{$id}/".$doc->filename);
            }
            else
            {
                $this->form->setFormTitle(basename($doc->title));
                $obj->text = $doc->content;
            }
            
            $this->form->setData($obj);
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     *
     */
    public static function onSave($param)
    {
        $id = (int) $param['id'];
        
        if (!empty($id))
        {
            try
            {
                TTransaction::open('communication');
                $doc = SystemDocument::find($id);
                $doc->checkPermission();
                
                if ($doc->filename)
                {
                    $file = "files/system/documents/{$id}/".$doc->filename;
                    if (is_writable($file))
                    {
                        file_put_contents($file, $param['text']);
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied'));
                    }
                }
                else
                {
                    $doc->content = $param['text'];
                    $doc->store();
                }
                TTransaction::close();
                //TScript::create("Template.closeRightPanel()");
                
                $param = [];
                $param['static'] = '1';
                $param['register_state'] = 'false';
                $param['type'] = 'file';
                $param['id'] = 'system_document_'.$id;
                
                AdiantiCoreApplication::loadPage('SystemDriveList', 'onOpen', $param);
            }
            catch (Exception $e)
            {
                new TMessage('error', $e->getMessage());
            }
        }
    }
    
    /**
     * Close side panel
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
