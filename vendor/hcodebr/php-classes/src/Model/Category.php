<?php 

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model
{

    public static function listAll()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories order by idcategory");        

    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
            ":idcategory"=>$this->getidcategory(), 
            ":descategory"=>$this->getdescategory(), 
        ));

        $this->setData($results[0]);
    }

    public function get($idcategory)
    {
            $sql = new Sql();

            $results = $sql->select("SELECT * FROM tb_categories
                                     WHERE idcategory = :idcategory", array(
                ":idcategory"=>$idcategory
            ));

            $this->setData($results[0]);

    }

    public function delete()
    {
        $sql = new Sql();

        $results = $sql->select("DELETE FROM tb_categories 
                                 where idcategory = :idcategory"
                                , array(":idcategory"=>$this->getidcategory(), 
        ));

    }




}


 ?>