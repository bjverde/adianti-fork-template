<?php
class TFormDinGenericDAO
{
    private string|null $database = null;
    private string|null $repository = null;
    private TFormDinPdoConnection|null $tpdo = null;

    /**
     * Seta os elmentos basicos para conectar no banco
     *
     * @param string $database   Nome da conexão ou novo do arquivo em /app/config
     * @param string $repository Nome da Classe do tipo Active Record no diretorio /app/model/maindatabase
     * @param object $tpdo objeto do tipo TFormDinPdoConnection
     */
    public function __construct($database = null, $repository = null, $tpdo = null)
    {
        if (empty($database) && empty($tpdo)) {
            throw new InvalidArgumentException('É necessário informar $database ou $tpdo');
        }
        $this->setRepository($repository);
        if (!empty($tpdo)) {
            $this->setTPDOConnection($tpdo);
            $this->setDatabase($tpdo->getDatabase());
        } else {
            $this->setDatabase($database);
            if (!empty($repository)) {
                $this->setTPDOConnection(new TFormDinPdoConnection($database));
            }
        }
    }
    public function getTPDOConnection()
    {
        $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)
        return $this->tpdo;
    }
    /**
     * Inicializa a conexão PDO se não estiver inicializada
     * @return void
     */
    private function initTPDOConnection()
    {
        if ($this->tpdo === null && $this->database !== null) {
            $this->setTPDOConnection(new TFormDinPdoConnection($this->database));
        }
    }
    public function setTPDOConnection(TFormDinPdoConnection $tpdo)
    {
        //FormDinHelper::validateObjTypeTPDOConnectionObj($tpdo,__METHOD__,__LINE__);
        $this->tpdo = $tpdo;
        if (!empty($tpdo->getDatabase())) {
            $this->database = $tpdo->getDatabase();
        } elseif (!empty($this->database)) {
            $tpdo->setDatabase($this->database);
        }
    }

    /**
     * Busca o nome do banco de dados
     *
     * @return string|null
     */
    public function getDatabase(): string|null
    {
        if ($this->tpdo !== null && !empty($this->tpdo->getDatabase())) {
            $this->database = $this->tpdo->getDatabase();
        }
        return $this->database;
    }

    /**
     * Seta o nome do banco de dados
     *
     * @param string|null $database
     * @return void
     */
    public function setDatabase(string|null $database): void
    {
        $this->database = $database;
        if ($this->tpdo !== null && $database !== null) {
            $this->tpdo->setDatabase($database);
        }
    }

    /**
     * Busca o nome do repository
     *
     * @return string|null
     */
    public function getRepository()
    {
        return $this->repository;
    }
    public function setRepository(string|null $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Busca informações do banco de dados
     *
     * @param string $debugDestino Destino do debug ('tela' ou 'log')
     * @return array
     */
    public function getDatabaseInfo(string $debugDestino = TFormDinPdoConnection::DEBUG_DESTINO_TELA)
    {
        $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)    
        return $this->getTPDOConnection()->getDatabaseInfo($debugDestino);
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
        $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)    
        return $this->getTPDOConnection()->executeSql($sql, $arrParams, $showDebugParam, $showInfo, $debugDestino);
    }

    /**
     * Executa comandos SQL
     *
     * @deprecated Utilize executeSql() em seu lugar.
     * @see TFormDinGenericDAO::executeSql()
     *
     * @param string $sql
     * @param array $values
     * @return mixed|null
     */
    public function execute(string $sql, array $values)
    {
        return $this->executeSql($sql, $values);
    }

    /**
     * Executa comandos SQL e retorna os registros
     *
     * @param string $sql
     * @return array|null Array associativo (chave => valor)
     */
    public function executeSelect(string $sql)
    {
        $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)    
        $tpdo = $this->getTPDOConnection();
        $prevFetch = $tpdo->getFech();
        $prevFormat = $tpdo->getOutputFormat();
        $prevCase = $tpdo->getCase();
        try {
            $tpdo->setFech(PDO::FETCH_ASSOC);
            $tpdo->setOutputFormat(ArrayHelper::TYPE_PDO);
            $tpdo->setCase(PDO::CASE_NATURAL);
            return $tpdo->executeSql($sql);
        } finally {
            $tpdo->setFech($prevFetch);
            $tpdo->setOutputFormat($prevFormat);
            $tpdo->setCase($prevCase);
        }
    }    

    /**
     * Executa comandos SQL e retorna a quantidade de registros
     *
     * @param string $sql
     * @return mixed|null
     */
    public function executeSelectCount(string $sql)
    {
        try {
            $result = $this->executeSelect($sql);
            return ArrayHelper::get($result, 0);
        } catch (Exception $e) {
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
    public static function getArrayKeyValue(string $colunaChave, string $colunaValor, ?array $list, int $typeCase = PDO::CASE_NATURAL): array
    {
        if (empty($list)) {
            return [];
        }
        return ArrayHelper::convertArray2PhpKeyValue($list, $colunaChave, $colunaValor, $typeCase);
    }

    /**
     * Executa um SELECT e transforma a lista de registros em um array associativo chave => valor
     *
     * @param string $colunaChave   Nome da coluna a ser usada como chave no array de saída
     * @param string $colunaValor   Nome da coluna a ser usada como valor no array de saída
     * @param string $sql           Instrução SELECT SQL a ser executada
     * @param array|null $values    Array de parâmetros para bind (posicional '?')
     * @return array Array no formato key => value
     * @throws Exception Em caso de erro na execução do SQL
     */
    public function getArrayKeyValueBySql(string $colunaChave, string $colunaValor, string $sql, ?array $values = null): array
    {
        $resultList = $this->executeSql($sql, $values);
        $case = $this->getTPDOConnection()?->getCase() ?? PDO::CASE_NATURAL;
        return ArrayHelper::convertArray2PhpKeyValue($resultList, $colunaChave, $colunaValor, $case);
    }

    /**
     * Busca registros baseado em uma criteria e transforma a lista em um array associativo chave => valor
     *
     * @param string $colunaChave   Nome da coluna a ser usada como chave no array de saída
     * @param string $colunaValor   Nome da coluna a ser usada como valor no array de saída
     * @param TCriteria|null $criteria Criteria de filtro/ordenação
     * @param bool $showDumpLogTela Se true, exibe dump de SQL na tela
     * @return array Array no formato key => value
     * @throws Exception Em caso de erro na consulta ou no repositório
     */
    public function getArrayKeyValueByCriteria(string $colunaChave, string $colunaValor, ?TCriteria $criteria = null, bool $showDumpLogTela = false): array
    {
        $resultList = $this->getArrayByCriteria($criteria, $showDumpLogTela);
        $case = $this->getTPDOConnection()?->getCase() ?? PDO::CASE_NATURAL;
        return ArrayHelper::convertArray2PhpKeyValue($resultList, $colunaChave, $colunaValor, $case);
    }

    /**
     * Busca arrays baseado em uma criteria
     *
     * @param TCriteria|null $criteria
     * @param bool $showDumpLogTela
     * @return array|null Array associativo (chave => valor)
     */
    public function getArrayByCriteria(?TCriteria $criteria = null, bool $showDumpLogTela = false)
    {
        try {
            $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)    
            $tpdo = $this->getTPDOConnection();
            $tpdo->openTransaction($showDumpLogTela);
            $repository = new TRepository($this->getRepository());
            $collections = $repository->load($criteria);
            $result = ArrayHelper::convertArray2OutputFormat($collections, ArrayHelper::TYPE_PDO, $tpdo->getCase());
            TTransaction::close();
            return $result;
        } catch (Exception $e) {
            TTransaction::rollback();
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Busca objetos baseados em uma criteria
     *
     * @param TCriteria|null $criteria
     * @param bool $showDumpLogTela
     * @return array|null Array de objetos (TRecord)
     */
    public function getListObjByCriteria(?TCriteria $criteria = null, bool $showDumpLogTela = false)
    {
        try {
            $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)
            $tpdo = $this->getTPDOConnection();
            $tpdo->openTransaction($showDumpLogTela);
            $repository = new TRepository($this->getRepository());
            $collections = $repository->load($criteria);
            $result = ArrayHelper::convertArray2OutputFormat($collections, ArrayHelper::TYPE_ADIANTI, $tpdo->getCase());
            TTransaction::close();
            return $result;
        } catch (Exception $e) {
            TTransaction::rollback();
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Conta registros baseado em uma criteria
     *
     * @param TCriteria|null $criteria
     * @param bool $showDumpLogTela
     * @return int|null
     */
    public function getCountByCriteria(?TCriteria $criteria = null, bool $showDumpLogTela = false)
    {
        try {
            $this->initTPDOConnection(); //Garante que a conexão PDO seja inicializada sob demanda (lazy initialization)
            $tpdo = $this->getTPDOConnection();
            $tpdo->openTransaction($showDumpLogTela);
            $repository = new TRepository($this->getRepository());
            $count = $repository->count($criteria);
            TTransaction::close();
            return $count;
        } catch (Exception $e) {
            TTransaction::rollback();
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}//fim classe