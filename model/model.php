<?php
    include_once(__DIR__."/../info.php");
    include_once("cnx.php");
    class ProgramModel extends Cnx
    {
        public $cone;
        public $gsent;
        public function informacion($params)
        {
            foreach ($params as $clave => $valor){
                $this->$clave=$valor;
            }
        }
        public function archivos($params)
        {
            foreach ($params as $clave => $valor){
                $this->$clave=$valor;
            }
        }
        public function contar($sql)
        {
            try
            {
                $this->cone = new Cnx();
                $this->gsent=$this->cone->prepare($sql);
                $this->gsent->execute();
                $this->cone = null;
                unset($this->cone);
                return $this->gsent->rowCount();
            }
            catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function capturar($sql)
        {
            try
            {
                $this->cone = new Cnx();
                $this->gsent = $this->cone->prepare($sql);
                $this->gsent->execute();
                $this->cone = null;
                unset($this->cone);
                return $this->gsent->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function ejecutar($sql)
        {
            try{
                $this->cone = new Cnx();
                try{
                    $cad = explode(" ", $sql);
                    if ($cad[0] === "INSERT") {
                        $this->gsent = $this->cone->exec($sql);
                        return [$this->cone->lastInsertId(),$this->cone->errorInfo()];
                    }
                    if ($cad[0] === "UPDATE") {
                        $this->gsent = $this->cone->exec($sql);
                        return [$this->gsent,$this->cone->errorInfo()];
                    }
                    if ($cad[0] === "DELETE") {
                        $this->gsent = $this->cone->exec($sql);
                        return [$this->gsent,$this->cone->errorInfo()];
                    }
                }catch (PDOException $e) {
                    $errorInfo = $this->cone->errorInfo();
                    if ($errorInfo[0] === '23000' && $errorInfo[1] === 1451) {
                        return $errorInfo;
                    } else {
                        return [$e, $errorInfo];
                    }
                }
            }catch(PDOException $e){return $e->getMessage();}
        }
    }