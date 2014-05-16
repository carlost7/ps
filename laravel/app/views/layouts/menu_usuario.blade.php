<div class="menu_principal">
      <div class="container">
            <ul class="nav nav-tabs">            
                  <li>
                        <a href="{{ URL::route('correos.index') }}" {{ ($activo==='correos')?'class=active':'' }}  />Correos</a>
                  </li>            
                  <li>
                        <a href="{{ URL::route('ftps.index') }}" {{ ($activo==='ftps')?'class=active':'' }}  />Ftps</a>
                  </li>
                  <li>
                        <a href="{{ URL::route('dbs.index') }}" {{ ($activo==='databases')?'class=active':'' }}  />Dbs</a>
                  </li>
            </ul>
      </div>
</div>