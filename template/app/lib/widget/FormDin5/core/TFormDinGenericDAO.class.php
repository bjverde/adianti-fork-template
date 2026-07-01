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
        $this->setDatabase($database);
        $this->setRepository($repository);
        if (empty($tpdo)) {
            $tpdo = new TFormDinPdoConnection($this->getDatabase());
        }
        $this->setTPDOConnection($tpdo);
    }
    public function getTPDOConnection()
    {
        return $this->tpdo;
    }
    public function setTPDOConnection(TFormDinPdoConnection $tpdo)
    {
        //FormDinHelper::validateObjTypeTPDOConnectionObj($tpdo,__METHOD__,__LINE__);
        $this->tpdo = $tpdo;
    }
    public function getDatabase()
    {
        return $this->database;
    }
    public function setDatabase(string|null $database)
    {
        $this->database = $database;
    }
    public function getRepository()
    {
        return $this->repository;
    }
    public function setRepository(string|null $repository)
    {
        $this->repository = $repository;
    }
    public function getDatabaseInfo()
    {
        try {
            TTransaction::open($this->getDatabase());
            $dbinfo = TConnection::getDatabaseInfo($this->getDatabase());
            var_dump($dbinfo);
            TTransaction::close();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function executeSelect(string $sql)
    {
        try {
            TTransaction::open($this->getDatabase());
            $connPdo = TTransaction::get();
            $connPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $sth = $connPdo->query($sql);
            $result = $sth->fetchAll();
            TTransaction::close();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function executeSelectCount(string $sql)
    {
        try {
            $result = $this->executeSelect($sql);
            return ArrayHelper::get($result, 0);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function execute(string $sql, array $values)
    {
        try {
            TTransaction::open($this->getDatabase());
            $connPdo = TTransaction::get();
            $connPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt  = $connPdo->prepare($sql);
            $resultSql = $stmt->execute($values);

            if (preg_match('/^insert/i', $sql) > 0) {
                $result = $connPdo->lastInsertId();
            } elseif (preg_match('/^update/i', $sql)) {
                $result = $stmt->rowCount();
            } elseif (preg_match('/^delete/i', $sql)) {
                $result = $stmt->rowCount();
            }
            TTransaction::close();
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function getArrayByCriteria(TCriteria $criteria, bool $showDumpLogTela = false)
    {
        try {
            TTransaction::open($this->getDatabase());
            $connPdo = TTransaction::get();
            $connPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //Mostra SQL na tela
            if ($showDumpLogTela == true) {
                TTransaction::dump( /* '/tmp/log.txt' */);
                TTransaction::setLoggerFunction(function ($message) {
                    echo $message . '<br>';
                });
            }

            //load using repository
            $repository = new TRepository($this->getRepository());
            $listArray   = $repository->load($criteria);
            TTransaction::close();
            return $listArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function getListObjByCriteria(TCriteria $criteria, bool $showDumpLogTela = false)
    {
        try {
            TTransaction::open($this->getDatabase());

            //Mostra SQL na tela
            if ($showDumpLogTela == true) {
                TTransaction::dump( /* '/tmp/log.txt' */);
                TTransaction::setLoggerFunction(function ($message) {
                    echo $message . '<br>';
                });
            }

            //load using repository
            $repository = new TRepository($this->getRepository());
            $listObjs   = $repository->load($criteria);
            TTransaction::close();
            return $listObjs;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}//fim classe