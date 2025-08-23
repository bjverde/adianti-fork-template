<?php
/**
 * SystemProfile2FAForm
 *
 * @version    8.2
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-template
 */
class SystemProfile2FAForm extends TPage
{
    private $form;
    
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
    }
    
    /**
     * onLoad
     */
    public function onLoad($param)
    {
        try
        {
            $ini  = AdiantiApplicationConfig::get();
            
            $this->form = new BootstrapFormBuilder;
            $this->form->setFormTitle(_t('Configure two-factor authentication'));
            $this->form->setClientValidation(true);
            $this->form->enableCSRFProtection();
            
            $enabled = new TCheckButton('enabled');
            $enabled->setIndexValue(1);
            $enabled->setUseSwitch(true, 'blue');
            
            TTransaction::open('permission');
            $user = SystemUser::newFromLogin( TSession::getValue('login') );
            TTransaction::close();
            
            if (!empty($user->otp_secret))
            {
                $enabled->setValue(1);
            }
            
            $otp = $user->otp_secret ? \OTPHP\TOTP::create($user->otp_secret) : \OTPHP\TOTP::create();
            $otp->setLabel($user->email);
            $otp->setIssuer($ini['general']['application']);

            // --- INÍCIO DA CORREÇÃO ---
            /* Em 2025 scanear o QrCode com o aplicativo do Google Authenticator estava gerando erro.
             * Seguindo a documentação, foi necessário aplicar rawurlencode() em cada parte da URI.
             * pois : não poderia ser codificado
             * o padrão do Adianti é usar otp->getProvisioningUri() na linha do $writer->writeString
             * 
            */
            // 1. Pega as partes separadamente
            $secret = $otp->getSecret();
            $issuer = $ini['general']['application'];
            $label  = $user->email;
            
            // 2. Aplica rawurlencode() em cada parte individualmente
            $encodedIssuer = rawurlencode($issuer);
            $encodedLabel  = rawurlencode($label);
            
            // 3. Monta a URI manualmente, mantendo o ":" literal
            $provisioningUri = "otpauth://totp/{$encodedIssuer}:{$encodedLabel}?" .
                               "secret={$secret}&" .
                               "issuer={$encodedIssuer}";            
            // --- FIM DA CORREÇÃO ---
            
            $provisioningUri = $provisioningUri; //Correção
            //$provisioningUri = $otp->getProvisioningUri(); //Padrão Adianti v8.2.0
            $htmlProvisioningUri = "<a href=\"{$provisioningUri}\" target=\"_blank\">Adicionar ao App de Autenticação</a>";

            // qrcodes
            $backend  = new \BaconQrCode\Renderer\Image\SvgImageBackEnd;
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle((int) 350, 0), $backend);
            $writer   = new \BaconQrCode\Writer($renderer);
            $qrcode   = $writer->writeString($provisioningUri);
            
            $secret  = new THidden('secret');
            $secret->setValue($otp->getSecret());
            
            $this->form->addContent([ new TLabel(_t('Scan the QR code with your phone to get started')) ]);
            $this->form->addContent([ _t('Use authencator app like Google Authenticator or Authy') ] );
            
            $this->form->addFields( [$secret] );
            $this->form->addContent( [$qrcode] );
            
            $this->form->addContent([ new TLabel(''), $htmlProvisioningUri ]);
            // $this->form->addContent([ new TLabel(_t('Secret key')), $otp->getSecret() ]);
            $this->form->addContent([ new TLabel(_t('Type')), 'TOTP (RFC 6238)' ]);
            
            $this->form->addFields( [new TLabel(_t('Enable 2FA'))],  [$enabled]);
            
            $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:check');
            $btn->class = 'btn btn-sm btn-primary';
            
            $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');
            
            parent::add($this->form);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * onSave
     */
    public static function onSave($param)
    {
        try
        {
            TTransaction::open('permission');
            
            $user = SystemUser::newFromLogin( TSession::getValue('login') );
            
            if (isset($param['enabled']) && $param['enabled'] == '1')
            {
                $user->otp_secret = $param['secret'];
            }
            else
            {
                $user->otp_secret = '';
            }
            
            $user->store();
            TTransaction::close();
            
            $pos_action = new TAction(['SystemProfileView', 'onLoad']);
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'), $pos_action);
            TScript::create("Template.closeRightPanel()");
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
    
    /**
     * on close
     */
    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}
