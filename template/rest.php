<?php
/**
 * rest.php
 *
 * Ponto de entrada unificado para os serviços REST/RESTful do Adianti Framework.
 *
 * ORIGEM:
 * Este arquivo foi ativado copiando o modelo de segurança nativa do framework
 * localizado em 'system/rest-secure.php.dist'.
 *
 * COMPORTAMENTO DE SEGURANÇA PADRÃO:
 * - Basic Auth: Usado principalmente para chamadas ao endpoint de login (/auth)
 *   que validam a chave global rest_key (application.php) e geram o Token JWT.
 * - Bearer Auth (JWT): Usado para validar chamadas subsequentes aos endpoints 
 *   protegidos com o cabeçalho "Authorization: Bearer <TOKEN>".
 *
 * COMO PROSSEGUIR CASO QUEIRA ALTERAR OU REMOVER A SEGURANÇA:
 * 1. Para desativar TOTALMENTE a segurança da API (Basic e JWT):
 *    Substitua o conteúdo completo deste arquivo ('rest.php') pelo conteúdo
 *    do arquivo original padrão 'system/rest.php.dist'.
 * 2. Para remover apenas a validação de JWT (Bearer) mantendo apenas a Basic:
 *    Comente ou remova o bloco de código condicional:
 *    "else if (substr($headers['Authorization'], 0, 6) == 'Bearer') { ... }"
 * 3. Para permitir chamadas públicas (sem Token) para determinadas classes específicas:
 *    Adicione uma lista branca (whitelist) de classes de serviço no início do bloco try, 
 *    antes de disparar o erro "if (empty($headers['Authorization']))".
 *
 * @author     Adianti Solutions (adaptado por Antigravity)
 * @version    8.4
 */
header('Content-Type: application/json; charset=utf-8');

// initialization script
require_once 'init.php';

class AdiantiRestServer
{
    public static function run($request)
    {
        $ini      = AdiantiApplicationConfig::get();
        $input    = json_decode(file_get_contents("php://input"), true);
        $request  = array_merge($request, (array) $input);
        $class    = isset($request['class']) ? $request['class']   : '';
        $method   = isset($request['method']) ? $request['method'] : '';
        $headers  = AdiantiCoreApplication::getHeaders();
        $response = NULL;
        
        $headers['Authorization'] = $headers['Authorization'] ?? ($headers['authorization'] ?? null); // for clientes that send in lowercase (Ex. futter)
        
        try
        {
            if (empty($headers['Authorization']))
            {
                throw new Exception( _t('Authorization error') );
            }
            else
            {
                if (substr($headers['Authorization'], 0, 5) == 'Basic')
                {
                    if (class_exists($class) && defined("{$class}::REST_KEY") and (!empty($class::REST_KEY)))
                    {
                        if ($class::REST_KEY !== substr($headers['Authorization'], 6))
                        {
                            http_response_code(401);
                            return json_encode( array('status' => 'error', 'data' => _t('Authorization error')));
                        }
                    }
                    else
                    {
                        if (empty($ini['general']['rest_key']))
                        {
                            throw new Exception( _t('REST key not defined') );
                        }
                        
                        if ($ini['general']['rest_key'] !== substr($headers['Authorization'], 6))
                        {
                            http_response_code(401);
                            return json_encode( array('status' => 'error', 'data' => _t('Authorization error')));
                        }
                    }
                }
                else if (substr($headers['Authorization'], 0, 6) == 'Bearer')
                {
                    ApplicationAuthenticationService::fromToken( substr($headers['Authorization'], 7) );
                }
                else
                {
                    http_response_code(403);
                    throw new Exception( _t('Authorization error') );
                }
            }
            
            $response = AdiantiCoreApplication::execute($class, $method, $request, 'rest');
            if (is_array($response))
            {
                array_walk_recursive($response, ['AdiantiStringConversion', 'assureUnicode']);
            }
            return json_encode( array('status' => 'success', 'data' => $response));
        }
        catch (Exception $e)
        {
            if(200 === http_response_code())
            {
                http_response_code(500);
            }

            return json_encode( array('status' => 'error', 'data' => $e->getMessage()));
        }
        catch (Error $e)
        {
            if(200 === http_response_code())
            {
                http_response_code(500);
            }

            return json_encode( array('status' => 'error', 'data' => $e->getMessage()));
        }
    }
}
print AdiantiRestServer::run($_REQUEST);