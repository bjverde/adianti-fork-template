<?php
/**
 * SystemPostCommentForm
 *
 * @version    1.0
 * @package    control
 * @subpackage communication
 * @author     Pablo Dall'Oglio
 * @author     Lucas Tomasi
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPostCommentForm extends TPage
{
    protected $form;
    private static $database = 'communication';
    private static $formName = 'form_PostCommentForm';
    
    /**
     * Page constructor
     */
    public function __construct( $param )
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle(_t("Post") ." #{$param['system_post_id']}");

        $system_post_id = new THidden('system_post_id');
        $comment = new TText('comment');
        $id = new THidden('id');

        $comment->addValidation("Comment", new TRequiredValidator()); 

        $system_post_id->setValue($param['system_post_id']);
        $id->setSize(200);
        $system_post_id->setSize(200);
        $comment->setSize('100%', 220);

        $row1 = $this->form->addFields([new TLabel(_t("Comment"), '#ff0000', '14px', null, '100%'),$system_post_id,$comment,$id]);
        $row1->layout = [' col-sm-12'];

        $btn_onsave = $this->form->addAction(_t("Save"), new TAction([$this, 'onSave']), 'fas:comment #ffffff');
        $btn_onsave->addStyleClass('btn-primary'); 
        
        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel(_t("Close"));
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);
    }
    
    /**
     *
     */
    public function onShow()
    {
    }
    
    /**
     * on save comment
     */
    public function onSave() 
    {
        try
        {
            TTransaction::open(self::$database);
            
            $this->form->validate();

            $data = $this->form->getData();

            $comment = new SystemPostComment;
            $comment->system_user_id = TSession::getValue('userid');
            $comment->system_post_id = $data->system_post_id;
            $comment->comment = $data->comment;
            $comment->store();

            TToast::show('success', _t("Record saved")); 

            AdiantiCoreApplication::loadPage('SystemPostFeedView', 'onShow', ['noscroll' => 1, 'register_state' => 'false']);

            TScript::create("Template.closeRightPanel();");

            TTransaction::close();
        }
        catch(Exception $e)
        {
            TTransaction::rollback();
            $this->form->setData($this->form->getData());
            new TMessage('error', $e->getMessage());
        }
    }
}
