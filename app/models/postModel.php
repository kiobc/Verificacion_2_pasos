<?php

class postsModel extends Model{
public static $t1 = 'posts';

function __construct()
{
    
}
static function all(){
$sql='SELECT* FROM posts ORDER BY id DESC';
return ($rows=parent::query($sql))?$rows:[];
}
static function by_id($id){
$sql='SELECT * FROM posts WHERE id=:id LIMIT 1';
return($rows=parent::query($sql,['id'=>$id]))?$rows[0]:[];
}
static function autorizado($id_usuario){
   $data=
   [
    'id_usuario'=>$id_usuario,
   'caducidad'=>time(),
   'ip'=>get_user_ip()
   ];
   $sql=
   'SELECT p.*
   FROM posts p
   WHERE p.tipo="2fa_autorizado" AND
   p.id_usuario=:id_usuario AND
   p.permalink>:caducidad AND
   p.ip=:ip
   ORDER BY p.creado desc
   LIMIT 1';
   return($rows=parent:: query($sql, [$data['id_usuario'], $data['caducidad'], $data['ip']]))? $rows[0]:[];
    }
//buscar si existe token autorizado
    static function has_token($id_usuario){
        $data=
        [
         'id_usuario'=>$id_usuario,
        'caducidad'=>time(),
        'ip'=>get_user_ip()
        ];
        $sql=
        'SELECT p.*
        FROM posts p
        WHERE p.tipo="2fa_token" AND
        p.id_usuario=:id_usuario AND
        p.permalink>:caducidad AND
        p.ip=:ip
        ORDER BY p.creado desc
        LIMIT 1';
        return($rows=parent:: query($sql, [$data['id_usuario'], $data['caducidad'], $data['ip']]))? $rows[0]:[];
         }
}
