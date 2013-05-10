<?php

namespace campanas\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;

class campanasTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getcampanas($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getAnunciosPorUsuario($id_usuario) {
        $rowset = $this->tableGateway->select(array('usuarios_id' => $id_usuario));
        return $rowset;
    }

    public function getFavoritos($id_usuario) {

        // $select = new Select();
//        $select->from(array('a' => 'anuncios')) 
//                 ->join(array('f' => 'favoritos'),                'a.id = f.anuncios_id')
//                ->where('f.usuarios_id = "'.$id_usuario.'"');
//   $select->from("anuncios')
//        ->join(array('f' => 'favoritos'), 'id = f.anuncios_id', \Zend\Db\Sql\Select::JOIN_INNER)
//        ->where('id = f.anuncios_id')
//          ->OR;
//        ->where('f.usuarios_id = $id_usuario');
//	       $select->from(array('a' => 'anuncios'))
//	      ->join(array('f' => 'favoritos'), 'a.id = f.anuncios_id');
//    
//	     $where = new  Where();
//	     $where->equalTo('f.usuarios_id', $id_usuario) ;
//	    $select->where($where);
//   
        // echo $select->getSqlString();

        $select = "SELECT * FROM anuncios INNER JOIN favoritos ON anuncios.id = favoritos.anuncios_id
				WHERE favoritos.usuarios_id =  '" . $id_usuario . "'";

        $statement = $this->tableGateway->getAdapter()->query($select);
        $results = $statement->execute();

        $returnArray = array();
// iterate through the rows
        foreach ($results as $result) {
            $returnArray[] = $result;
        }

        return $returnArray;

        //$rowset = $this->tableGateway->select($select);
        // return $rowset;
    }

    public function getAnuncios($busqueda) {

        $where = new Where();
        $where->like('titulo', '%' . $busqueda . '%');
        $where->or;
        $where->like('descripcion', '%' . $busqueda . '%');

        $rowset = $this->tableGateway->select($where);


        return $rowset;
    }

    public function savecampanas(campanas $campanas) {
        $data = array(
            'id' => $campanas->id,
            'usuarios_id' => $campanas->usuarios_id,
            'titulo' => $campanas->titulo,
            //isset($data['publicacion'])) ? $data['publicacion'] : null
            'descripcion' => $campanas->descripcion,
            'precio' => $campanas->precio,
            'publicacion' => $campanas->publicacion,
            'visitas' => $campanas->visitas,
            'listado' => $campanas->listado,
            'renovacion' => date('Y-m-d H:i:s'),
            'lltlng' => $campanas->lltlng,
            'direccion' => $campanas->direccion,
            'imagen' => substr($campanas->imagen['tmp_name'], 7),
            'imagen2' => substr($campanas->imagen2['tmp_name'], 7)
        );

        $id = (int) $campanas->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getcampanas($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletecampanas($id) {
        $this->tableGateway->delete(array('id' => $id));
    }

}