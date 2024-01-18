<?php
/**
 * SystemSessionVarsView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemSessionVarsView extends TPage
{
    public function __construct()
    {
        // parent classs constructor
        parent::__construct();
        
        $config = AdiantiApplicationConfig::get();
        ini_set('highlight.comment', $config['highlight']['comment']);
        ini_set('highlight.default', $config['highlight']['default']);
        ini_set('highlight.html',    $config['highlight']['html']);
        ini_set('highlight.keyword', $config['highlight']['keyword']);
        ini_set('highlight.string',  $config['highlight']['string']);
        
        // wrap the page content
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        
        $vars = [];
        $vars['logged'] = 'Retuns if the user is logged';
        $vars['login'] = 'Retuns the user login';
        $vars['userid'] = 'Retuns the user id';
        $vars['username'] = 'Retuns the user name';
        $vars['usermail'] = 'Retuns the user e-mail';
        $vars['usercustomcode'] = 'Retuns the user custom code';
        $vars['userunitid'] = 'Retuns the user unit id';
        $vars['userunitname'] = 'Retuns the user unit name';
        $vars['userunitcustomcode'] = 'Retuns the user unit custom code';
        $vars['unit_database'] = 'Retuns the user unit database';
        $vars['userunitname'] = 'Retuns the user unit name';
        $vars['user_language'] = 'Retuns the user language';
        $vars['usergroupids'] = 'Retuns the user groups ids';
        $vars['userunitids'] = 'Retuns the user units ids';
        $vars['userroleids'] = 'Retuns the user roles ids';
        $vars['userroles'] = 'Retuns the user roles custom codes';
        
        
        foreach ($vars as $var => $description)
        {
            $hbox = new THBox;
            $hbox->style = 'display:flex';
            
            $col1 = new TElement('div');
            $col2 = new TVBox;
            
            $hbox->add($col1)->{'style'} = 'width:48%;display:table-cell';
            $hbox->add($col2)->{'style'} = 'width:50%;display:table-cell;margin-left:10px';
            
            // scroll to put the source inside
            $wrapper = new TElement('div');
            $wrapper->class = 'sourcecodewrapper';
            $source = new TSourceCode;
            $wrapper->add($source);
            $source->loadString("<?php\nTSession::getValue('$var');");
            
            $col1->add(new TLabel($description));
            $col1->add($wrapper);
            
            $col2->add(new TLabel('Current value'));
            $col2->add('<pre style="border:none;padding:0;background:none">'.var_export(TSession::getValue($var),true).'</pre>');
            
            $vbox->add($hbox);
        }
        
        parent::add($vbox);
    }
}
