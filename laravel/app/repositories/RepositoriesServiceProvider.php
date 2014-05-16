<?php

use Illuminate\Support\ServiceProvider;

/**
 * Description of RepositoriesServiceProvider
 *
 * @author carlos
 */
class RepositoriesServiceProvider extends ServiceProvider
{

      public function register()
      {
            $this->app->bind(
                    'CorreoRepository', 'CorreosRepositoryEloquent'
            );
            $this->app->bind(
                    'UsuariosRepository', 'UsuariosRepositoryEloquent'
            );
            $this->app->bind(
                    'DominioRepository', 'DominioRepositoryEloquent'
            );
            $this->app->bind(
                    'FtpsRepository', 'FtpsRepositoryEloquent'
            );
            $this->app->bind(
                    'DatabaseRepository', 'DatabaseRepositoryEloquent'
            );
      }

}
