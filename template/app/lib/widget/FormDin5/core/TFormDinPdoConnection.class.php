<?php
/*
 * ----------------------------------------------------------------------------
 * Formdin 5 Framework
 * SourceCode https://github.com/bjverde/formDin5
 * @author Reinaldo A. Barrêto Junior
 * 
 * É uma reconstrução do FormDin 4 Sobre o Adianti 7.X
 * @author Luís Eugênio Barbosa do FormDin 4
 * 
 * Adianti Framework é uma criação Adianti Solutions Ltd
 * @author Pablo Dall'Oglio
 * ----------------------------------------------------------------------------
 * This file is part of Formdin Framework.
 *
 * Formdin Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License version 3
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License version 3
 * along with this program; if not,  see <http://www.gnu.org/licenses/>
 * or write to the Free Software Foundation, Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA  02110-1301, USA.
 * ----------------------------------------------------------------------------
 * Este arquivo é parte do Framework Formdin.
 *
 * O Framework Formdin é um software livre; você pode redistribuí-lo e/ou
 * modificá-lo dentro dos termos da GNU LGPL versão 3 como publicada pela Fundação
 * do Software Livre (FSF).
 *
 * Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA
 * GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou
 * APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/LGPL em português
 * para maiores detalhes.
 *
 * Você deve ter recebido uma cópia da GNU LGPL versão 3, sob o título
 * "LICENCA.txt", junto com esse programa. Se não, acesse <http://www.gnu.org/licenses/>
 * ou escreva para a Fundação do Software Livre (FSF) Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02111-1301, USA.
 */

class TFormDinPdoConnection
{
    const DBMS_ACCESS   = 'ACCESS';
    const DBMS_FIREBIRD = 'ibase';
    const DBMS_MYSQL    = 'mysql';
    const DBMS_ORACLE   = 'oracle';
    const DBMS_POSTGRES = 'pgsql';
    const DBMS_SQLITE   = 'sqlite';
    const DBMS_SQLSERVER= 'sqlsrv';

    const DEBUG_TARGET_SCREEN = 'screen';
    const DEBUG_TARGET_LOG    = 'log';
    const DEBUG_DESTINO_TELA  = 'tela';
    const DEBUG_DESTINO_LOG   = 'log';

    private $database = null;
    private $fech = null;
    private $case = null;
    private $outputFormat = null;
    private $outputFormatDefault = ArrayHelper::TYPE_ADIANTI;
    private $caseDefault = PDO::CASE_UPPER;
    
    private $host;
    private $port;
    private $name;
    private $user;
    private $pass;
    private $type;
    private $opts;//optional parameters

    /**
     * Facilitardor de conexão com o banco de dados
     *
     * @param string $database : nome da conexão. É o nome do arquivo INI ou PHP de configuração do banco. Informe NULL para setar cada propriedade manualmente
     * @param const $outputMode: DEFAULT = ArrayHelper::TYPE_PDO. ArrayHelper::TYPE_FORMDIN, ArrayHelper::TYPE_ADIANTI
     * @param const $fech: DEFAULT = PDO::FETCH_OBJ  array de Objet, PDO::FETCH_ASSOC - array simples
     * @param const $case use PDO case. DEFAULT = CASE_NATURAL.  https://www.php.net/manual/pt_BR/pdo.prepare.php
     */
    public function __construct($database = null,$outputFormat = null,$fech = null,$case = null)
    {
        if(!empty($database)){
            $this->setDatabase($database);
        }
        $this->setOutputFormat($outputFormat);
        $this->setFech($fech);
        $this->setCase($case);
    }

    public function setDatabase($database)
    {
        if( empty($database) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_EMPTY_INPUT);
        }
        if( !is_string($database) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_TYPE_WRONG.' o nome data base dever ser uma string');
        }
        $this->database = $database;
        try {
            $arrParams = TConnection::getDatabaseInfo($database);
            $type = ArrayHelper::get($arrParams,'type');
            if(!empty($type)){
                $this->setDdms($type);
            }
        } catch (Throwable $e) {
            // Ignora se o arquivo de configuração não existir (ex: conexões dinâmicas por array)
        }
    }
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Defini ao case do array de retorno. Veja 
     * https://www.php.net/manual/pt_BR/pdostatement.fetch.php
     * 
     * PDO::FETCH_ASSOC - array simples
     * PDO::FETCH_OBJ   - array de Objeto
     *
     * @param @param const $case. DEFAULT = PDO::FETCH_OBJ
     * @return void
     */    
    public function setFech($fech)
    {
        if(empty($fech)){
            $fech = PDO::FETCH_OBJ;
        }
        $this->fech = $fech;
    }
    public function getFech()
    {
        return $this->fech;
    }

    /**
     * Defini ao case do array de retorno. Veja 
     * https://www.php.net/manual/pt_BR/pdo.setattribute.php
     * 
     * PDO::CASE_LOWER
     * PDO::CASE_NATURAL
     * PDO::CASE_UPPER
     *
     * @param @param const $case. DEFAULT = PDO::CASE_NATURAL
     * @return void
     */
    public function setCase($case)
    {
        if(is_null($case) || $case === ''){
            $case = $this->caseDefault;
        }
        $this->case = $case;
    }
    public function getCase()
    {
        return $this->case;
    }

    /**
     * Determina o tipo array das consultas
     * @param const $outputMode: Default = ArrayHelper::TYPE_ADIANTI. ArrayHelper::TYPE_PDO, ArrayHelper::TYPE_FORMDIN
     */
    public function setOutputFormat($outputFormat)
    {
        if(empty($outputFormat)){
            $outputFormat = $this->outputFormatDefault;
        }
        $this->outputFormat = $outputFormat;
    }
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * Retorna um array com o tipo de SGBD e descrição
     *
     * @return array
     */
    public static function getListDBMS()
    {
        $list = array();
        //$list[self::DBMS_ACCESS]='Access';
        //$list[self::DBMS_FIREBIRD]='FIREBIRD';
        $list[self::DBMS_MYSQL]='MariaDB ou MySQL';
        //$list[self::DBMS_ORACLE]='Oracle';
        $list[self::DBMS_POSTGRES]='PostgreSQL';
        $list[self::DBMS_SQLITE]='SqLite';
        $list[self::DBMS_SQLSERVER]='SQL Server';
        return $list;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    public function setHost($host)
    {
        $this->host = $host;
    }
    
    public function getPort()
    {
        return $this->port;
    }
    public function setPort($port)
    {
        $this->port = $port;
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function getPass()
    {
        return $this->pass;
    }
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function getDbms()
    {
        return $this->getType();
    }
    public function setDdms($dbms)
    {
        return $this->setType($dbms);
    }
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $listType = self::getListDBMS();
        $inArray = ArrayHelper::has($type,$listType);
        if (!$inArray) {
            throw new InvalidArgumentException('Type DBMS is not value valid');
        }
        $this->type = $type;
    }

    public function setOpts($opt)
    {
        $this->opts = $opt;        
    }

    public function getOpts()
    {
        return $this->opts;
    }
    
    public function getConfigConnect()
    {
        $result = array();
        $databese = $this->getDatabase();
        $type = $this->getType();
        $name = $this->getName();
        $conditionArrayConnectEmpty = empty($type) || empty($name);
        if( empty($databese) && $conditionArrayConnectEmpty ){
            throw new InvalidArgumentException('Fail to configure the database! Please input correct config');
        }

        if ($type===self::DBMS_SQLSERVER){
            $this->setOpts('TrustServerCertificate=yes');        
        }
        
        $db =  null;
        if(!$conditionArrayConnectEmpty){
            $db = array();
            $db['host'] = $this->getHost();
            $db['port'] = $this->getPort();
            $db['name'] = $name;
            $db['user'] = $this->getUser();
            $db['pass'] = $this->getPass();
            $db['type'] = $type;
            $db['opts'] = $this->getOpts();
        }
        
        $result['database'] = $databese;
        $result['db'] = $db;
        
        return $result;
    }

    public function getDatabaseInfo(string $debugDestino = self::DEBUG_DESTINO_TELA)
    {
        try {
            $configConnect = $this->getConfigConnect();
            $database = $configConnect['database'];
            $db = $configConnect['db'];
            if (!empty($db)) {
                $dbinfo = $db;
            } else {
                $dbinfo = TConnection::getDatabaseInfo($database);
            }
            if (empty($dbinfo)) {
                throw new Exception("Database config not found for '{$database}'");
            }
            $this->outputDebug($dbinfo, $debugDestino, 'DATABASE INFO');
            return $dbinfo;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Exibe ou grava em log informações de debug
     *
     * @param mixed $data Dados a serem exibidos (string ou array)
     * @param string $debugDestino Destino do debug ('tela'/'screen' ou 'log')
     * @param string|null $title Título opcional
     * @return void
     */
    public function outputDebug($data, string $debugDestino = self::DEBUG_DESTINO_TELA, ?string $title = null)
    {
        $isLog = in_array(strtolower($debugDestino), ['log', 'php_log', 'error_log', self::DEBUG_TARGET_LOG]);
        
        $msg = '';
        if (!empty($title)) {
            $msg .= "=== {$title} ===" . PHP_EOL;
        }
        if (is_string($data)) {
            $msg .= $data . PHP_EOL;
        } else {
            $msg .= print_r($data, true) . PHP_EOL;
        }

        if ($isLog) {
            error_log($msg);
        } else {
            if (php_sapi_name() === 'cli') {
                echo $msg;
            } else {
                echo '<pre>' . htmlspecialchars($msg) . '</pre>';
            }
        }
    }

    /**
     * Retorna o valor Default da porta do SGBD
     *
     * @return string
     */
    public function getDefaulPort() {
        $result = null;
        switch( $this->getType() ) {
            case self::DBMS_POSTGRES:
                $result = '5432';
            break;
            case self::DBMS_MYSQL:
                $result = '3306';
            break;
            case self::DBMS_SQLSERVER:
                $result = '1433';
            break;
            case self::DBMS_ORACLE:
                $result = '1521';
            break;
        }
		return $result;
    }
    
    public function convertArrayResult($arrayData)
    {
        $outputFormat = $this->getOutputFormat();
        if( $outputFormat != $this->outputFormatDefault ){
            $case = $this->getCase();
            $result = ArrayHelper::convertArray2OutputFormat($arrayData,$outputFormat,$case);
        }else{
            $result = $arrayData;
        }        
        return $result;
    }

    /**
     * Verifica se quantidade de parametros está correta
     * @param string $sql      -1: string sql do comando
     * @param array $arrParams -2: array com o valores para bind do sql
     * @return void
     */
    public function validarQtdParametros($sql,$arrParams)
    {   
        if( empty($sql) || !is_string($sql) ){
            throw new InvalidArgumentException(TFormDinMessage::ERROR_SQL_NULL);
        }
        if ( strpos( $sql, '?' ) > 0 && !is_array( $arrParams ) ) {
            throw new InvalidArgumentException(TFormDinMessage::ERROR_SQL_PARAM);
        }        
        if ( strpos( $sql, '?' ) > 0 && is_array( $arrParams ) && count( $arrParams ) == 0 ) {
            throw new InvalidArgumentException(TFormDinMessage::ERROR_SQL_PARAM);
        }
        if ( strpos( $sql, '?' ) > 0 && is_array( $arrParams ) && count( $arrParams ) > 0 ) {
            $qtd1 = substr_count( $sql, '?' );
            $qtd2 = count( $arrParams );
            
            if ( $qtd1 != $qtd2 ) {
                throw new InvalidArgumentException(TFormDinMessage::ERROR_SQL_PARAM);
            }
        }
    }

    /**
     * Recebe um array de entrada de dados e verifica o tipo está correto
     *
     * @param array $arrDados
     * @return array
     */
    public function prepareArray( $arrDados = null ) {
        $result = array();        
        if ( is_array( $arrDados ) ) {
            foreach( $arrDados as $k => $v ) {
                if ( !is_null($v) && !empty($v) ){
                    $arrDados[ $k ] = $v;
                } else if( is_int($v) ) {
                    $arrDados[ $k ] = $v;
                } else if( $v === '0' ) {
                    $arrDados[ $k ] = $v;
                } else {
                    $arrDados[ $k ] = null;
                }
            }
            $result = $arrDados;
        }
        return $result;
    }

    /**
     * Executa comandos SQL (SELECT, INSERT, UPDATE, DELETE, Stored Procedures, etc.)
     *
     * Gerenciamento Transacional (Integridade de Dados):
     * - Transação Ativa: Se uma transação do Adianti já estiver aberta para o banco de dados
     *   (via TTransaction::open()), a conexão ativa é reutilizada e o método NÃO fecha a transação,
     *   permitindo múltiplos comandos dentro do mesmo escopo transacional de negócio.
     * - Transação Local: Caso não exista transação ativa, o método abre uma transação local,
     *   executa o comando, realiza TTransaction::close() no sucesso e TTransaction::rollback()
     *   em caso de erro, garantindo que não haja transações vazadas ou órfãs.
     *
     * Retornos por tipo de instrução:
     * - SELECT / RETURNING / WITH: Array de registros formatado conforme outputFormat e case.
     * - INSERT: ID do último registro inserido (com suporte a SCOPE_IDENTITY no SQL Server).
     * - UPDATE / DELETE: Quantidade de linhas afetadas (rowCount).
     * - Procedures / PRAGMA: Resultados retornados pela execução.
     *
     * @param string $sql           Instrução SQL a ser executada
     * @param array|null $arrParams Array de parâmetros para bind (posicional '?')
     * @param bool $showDebugParam  Se true, exibe/grava em log a query e os parâmetros
     * @param bool $showInfo        Se true, exibe/grava em log as informações do banco de dados
     * @param string $debugDestino  Destino do debug ('tela' ou 'log')
     * @return mixed Array de resultados, ID gerado, quantidade de linhas afetadas ou boolean
     * @throws Exception Em caso de erro na preparação, validação ou execução do SQL
     */
    public function executeSql($sql, $arrParams = null, bool $showDebugParam = false, bool $showInfo = false, string $debugDestino = 'tela')
    {
        $isLocalTransaction = false;
        try {
            if ($showInfo) {
                $this->getDatabaseInfo($debugDestino);
            }

            if ($showDebugParam) {
                $debugData = [
                    'sql' => $sql,
                    'arrParams' => $arrParams
                ];
                $this->outputDebug($debugData, $debugDestino, 'DEBUG PARAMETERS');
            }

            $this->validarQtdParametros($sql, $arrParams);
            $arrParams = $this->prepareArray( $arrParams );
            $configConnect = $this->getConfigConnect();
            $database = $configConnect['database'];
            $db = $configConnect['db'];
            $case     = $this->getCase();
            $fech     = $this->getFech();
            
            if (TTransaction::get() && TTransaction::getDatabase() === $database) {
                $conn = TTransaction::get();
            } else {
                TTransaction::open($database, $db); // abre uma transação local
                $conn = TTransaction::get();
                $isLocalTransaction = true;
            }
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_CASE, $case);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fech);
            
            // No SQL Server o lastInsertId() do pdo_sqlsrv executa SELECT @@IDENTITY, que retorna
            // o ultimo IDENTITY da sessão em qualquer escopo. Se a tabela tiver trigger (ex: auditoria)
            // que insere em outra tabela com IDENTITY, o ID retornado é o da tabela de log.
            // SCOPE_IDENTITY() precisa estar no mesmo batch do INSERT para ficar no mesmo escopo.
            $isSqlServerInsert = ( $conn->getAttribute(PDO::ATTR_DRIVER_NAME) == self::DBMS_SQLSERVER )
                              && ( preg_match( '/^insert/i', $sql ) > 0 );
            $sqlExecute = $sql;
            if ( $isSqlServerInsert ) {
                $sqlExecute = rtrim($sql, " \t\n\r\0\x0B;").'; SELECT SCOPE_IDENTITY() AS last_inserted_id';
            }

            //$stmt = $conn->query($sql);    // realiza a consulta
            $stmt = $conn->prepare( $sqlExecute );
            $result = $stmt->execute( $arrParams );

            if ( $result ) {
                
                if ( preg_match( '/^select/i', $sql ) > 0 || preg_match( '/returning/i', $sql ) > 0 || preg_match( '/^with/i', $sql ) > 0  ) {
                    $result = $stmt->fetchall();
                    $result = $this->convertArrayResult($result);
                }else if( $isSqlServerInsert ){
                    // Avança pelos resultsets sem colunas (rowcount do INSERT e das triggers) até o do SCOPE_IDENTITY
                    while ( $stmt->columnCount() == 0 && $stmt->nextRowset() ) {
                    }
                    $result = $stmt->fetchColumn();
                }else if( preg_match( '/^insert/i', $sql ) > 0  ){
                    $result = $conn->lastInsertId();
                }else if( preg_match( '/^update/i', $sql ) > 0  ){
                    $result = $stmt->rowCount();
                }else if( preg_match( '/^delete/i', $sql ) > 0  ){
                    $result = $stmt->rowCount();
                // @codeCoverageIgnoreStart
                }else if( preg_match( '/^exec/i', $sql ) > 0  ){ // Para stored procedure do MS SQL Server                                        
                    $res = array();
                    //https://github.com/bjverde/formDin/issues/164
                    while($stmt->columnCount()) {
                        $result = $stmt->fetchall();
                        $result = $this->convertArrayResult($result);
                        $res[] = $result;
                        $stmt->nextRowset();
                    }
                    $result = $res;
                }else if( preg_match( '/^call/i', $sql ) > 0  ){ // Para stored procedure do MySQL
                    $result = $stmt->fetchall();
                    $result = $this->convertArrayResult($result);
                // @codeCoverageIgnoreEnd
                }else if( preg_match( '/^PRAGMA/i', $sql ) > 0  ){//Informações do SqLite
                    $result = $stmt->fetchall();
                    $result = $this->convertArrayResult($result);
                }
            }
            if ($isLocalTransaction) {
                TTransaction::close();         // fecha a transação apenas se foi aberta localmente neste método
            }
            return $result;
        }
        catch (Exception $e) {
            if ($isLocalTransaction) {
                TTransaction::rollback();
            }
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Transforma uma lista de registros em um array associativo chave => valor
     *
     * @deprecated Utilize ArrayHelper::convertArray2PhpKeyValue()
     * @see ArrayHelper::convertArray2PhpKeyValue()
     * @codeCoverageIgnore
     *
     * @param string $colunaChave Nome da coluna chave
     * @param string $colunaValor Nome da coluna valor
     * @param array|null $list Lista de registros
     * @param int $typeCase PDO::CASE_NATURAL, PDO::CASE_UPPER, PDO::CASE_LOWER
     * @return array
     */
    public static function getArrayKeyValue($colunaChave, $colunaValor, $list, $typeCase = PDO::CASE_NATURAL)
    {
        if (empty($list)) {
            return array();
        }
        return ArrayHelper::convertArray2PhpKeyValue($list, $colunaChave, $colunaValor, $typeCase);
    }


    /**
     * Executa um SELECT e transforma a lista de registros em um array associativo chave => valor
     *
     * @deprecated Utilize TFormDinGenericDAO::getArrayKeyValueBySql()
     * @see TFormDinGenericDAO::getArrayKeyValueBySql()
     * @codeCoverageIgnore
     *
     * @param string $colunaChave   Nome da coluna a ser usada como chave no array de saída
     * @param string $colunaValor   Nome da coluna a ser usada como valor no array de saída
     * @param string $sql           Instrução SELECT SQL a ser executada
     * @param array|null $values    Array de parâmetros para bind (posicional '?')
     * @return array Array no formato key => value
     * @throws Exception Em caso de erro na execução do SQL
     */
    public function getArrayKeyValueBySql($colunaChave, $colunaValor, $sql, $values = null)
    {
        $resultList = $this->executeSql($sql, $values);
        $case = $this->getCase() ?? PDO::CASE_NATURAL;
        $result = self::getArrayKeyValue($colunaChave, $colunaValor, $resultList, $case);
        return $result;
    }

    /**
     * Abre a transação com as configurações da conexão atual (por arquivo INI/PHP ou array dinâmico)
     *
     * @param bool $showDumpLogTela Ativa dump de SQL na tela
     * @return void
     */
    public function openTransaction(bool $showDumpLogTela = false): void
    {
        $configConnect = $this->getConfigConnect();
        $database = $configConnect['database'];
        $db = $configConnect['db'];

        TTransaction::open($database, $db);
        if ($showDumpLogTela) {
            TTransaction::dump();
            TTransaction::setLoggerFunction(function ($message) {
                echo $message . '<br>';
            });
        }
    }

    /**
     * @codeCoverageIgnore
     * Faz um Select usando o TCriteria
     *
     * @deprecated Utilize TFormDinGenericDAO::getArrayByCriteria() ou TFormDinGenericDAO::getListObjByCriteria()
     * @see TFormDinGenericDAO::getArrayByCriteria()
     * @see TFormDinGenericDAO::getListObjByCriteria()
     *
     * @param TCriteria|null $criteria       - 01: Obj TCriteria
     * @param string|null    $repositoryName - 02: nome de classe em app/model
     * @param bool           $showDumpLogTela - 03: se exibe o log SQL na tela
     * @return array Adianti
     */    
    public function selectByTCriteria(?TCriteria $criteria=null, $repositoryName=null, bool $showDumpLogTela = false)
    {
        throw new Exception('Troque por TFormDinGenericDAO::getArrayByCriteria() ou getListObjByCriteria()');
    }

    /**
     * @codeCoverageIgnore
     * Faz um Select Count usando o TCriteria
     *
     * @deprecated Utilize TFormDinGenericDAO::getCountByCriteria()
     * @see TFormDinGenericDAO::getCountByCriteria()
     *
     * @param TCriteria|null $criteria       - 01: Obj TCriteria
     * @param string|null    $repositoryName  - 02: nome de classe
     * @param bool           $showDumpLogTela - 03: se exibe o log SQL na tela
     * @return int
     */
    public function selectByTCriteriaCount(?TCriteria $criteria = null, $repositoryName = null, bool $showDumpLogTela = false)
    {
        throw new Exception('Troque por TFormDinGenericDAO::getCountByCriteria()');
    }
}