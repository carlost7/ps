<?php

/**
 * Modelo para manejar los datos del dominio
 *
 * @author carlos
 */
class Dominio extends Eloquent {

      protected $table = 'dominios';
      protected $fillable = array('user_id', 'dominio', 'activo', 'plan_id');

      public function user()
      {
            return $this->belongsTo('User','usuario_id','id');
      }

      public function plan()
      {
            return $this->belongsTo('Plan');
      }

      public function correos()
      {
            return $this->hasMany('Correo', 'dominio_id', 'id');
      }

      public function ftps()
      {
            return $this->hasOne('Ftp', 'dominio_id', 'id');
      }

      public function dbs()
      {
            return $this->hasMany('Database', 'dominio_id', 'id');
      }

}
