<?php

class usuarioModel extends Model{
public static $t1 = 'usuarios';

function __construct()
{
}
static function all(){
$sql='SELECT* FROM usuarios ORDER BY id DESC';
return ($rows=parent::query($sql))?$rows:[];
}
static function by_id($id){
$sql='SELECT * FROM usuarios WHERE id=:id LIMIT 1';
return($rows=parent::query($sql,['id'=>$id]))?$rows[0]:[];

}
static function by_usuario($usuario){
    $sql='SELECT * FROM usuarios WHERE usuario=:usuario LIMIT 1';
    return($rows=parent::query($sql,['usuario'=>$usuario]))?$rows[0]:[];
    
    }
    static function by_hash($hash){
        $sql='SELECT * FROM usuarios WHERE hash=:hash LIMIT 1';
        return($rows=parent::query($sql,['hash'=>$hash]))?$rows[0]:[];
        
        }    
}