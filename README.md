# ucatolica
# Montamos el ambiente de desarrollo(optional en caso de montar el ambiente docker desde cero)

#---nos apoyamos del framework laravel 5.8 para conocer sobre POO, MVC y del mundo de desarrollo web 

        https://laravel.com/docs/5.8/installation

#--descargar laravel 5.8 desde aqui:

#--descomprimir laravel 5.8, cambiar nombre y poner el que gusten, "ejemplo claseu".

#--descargar del siguiente link archivos a descomprimir: https://github.com/alejofdezm/ucatolica/raw/master/descomprimir.zip

#--descomprimir el archivo con nombre descomprimir.zip dentro de la carperta de laravel anterior "ejemplo: claseu".

#--Siempre que se quieran volver super usuario, deben de ejecutar: sudo su y luego su contraseña.

#--en caso de montar el ambiente desde cero, compilar el servidor web de docker con el siguiente comando: 

        docker build -t laraveldeveloper ./  
        
        (paso opcional, solo si es nuevo el ambiente, en las maquinas virtual entregadas no hay que hacer esto, solo si quieren montar su propia maquina virtual o local)


## --visto en clases(levantar su ambiente en clase)

#-- abrimos visual studio code (editor que usaremos en clases)
#-- abrimos la carpeta con visual code donde descomprimimos laravel "ejemplo claseu" y donde alojamos los archivos que vienen comprimidas en el archivo descomprimir.zip 

#-- luego abrimos la terminal
#-- se vuelven super usuario: sudo su

#-- levantan el ambiente de desarrollo con el siguiente comando:

     docker-compose up -d
 
 #- el comando anterior nos lenvata lo siguiente:
  servidor web en el puerto 80, ruta de consulta: http://127.0.0.1/
  servidor de mysql con nombre: db
  servidor de administración de sus ambientes (portainer): http://127.0.0.1:9000
  servidor para administrar mysql por medio de phpmyadmin: http://127.0.0.1:8081/
  
#instalamos las dependencias que necesita laravel para poder funcionar de forma basica:

     docker run --rm -it  -v $(pwd):/app composer composer install 

#-- luego de cada comando ejecutado que agregue nuevos archivos, es bueno dar permisos de lectura y escritura a toda la carpeta (el comando anterior agrego nuevos archivos que son las dependecias basicas):
     
     chmod -R 777 ./

#--2 - docker exec -it servidorweb  bash

 #---2.1 - sudo chown -R $USER:$USER ./
 
 #---2.5 - sudo chown -R www-data:www-data ./
 
 

#---4 - docker ps

    #4.5 - docker pull composer
    
#5 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan key:generate

#6 - docker-compose exec laraveldeveloper nano .env (optional)

#7 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan config:cache

# -- https://medium.com/@cvallejo/roles-usuarios-laravel-2e1c6123ad
    #7.1 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:auth 

#--- roles

    #7.2 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Role -m
   
   app/database/migrations/xxxx_xx_xx_create_roles_table.php
    
     Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    app/User.php
    
    use App\Role;
    
     public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    
    app/Role.php
    
    use App\User;
    
     public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    
    #7.3 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:migration create_role_user_table
    
    app/database/migrations/xxxx_xx_xx_create_role_user_table.php
    
     Schema::create('role_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        
       #7.3.1 - docker exec -it servidorweb php artisan migrate --force  
    
    #7.4 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder RoleTableSeeder
    
    app/database/seeds/RoleTableSeeder.php
    
     public function run()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->description = 'Administrator';
        $role->save();
        
        $role = new Role();
        $role->name = 'user';
        $role->description = 'User';
        $role->save();
    }
    app/database/seeds/DatabaseSeeder.php
    
     $this->call(RoleTableSeeder::class);
     
    #7.5 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder UsersTableSeeder
    
    app/database/seeds/UsersTableSeeder.php
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_user = Role::where('name', 'user')->first();

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@domain.com';
        $user->password = bcrypt('admin123'); 
        $user->save();
        $user->roles()->attach($role_admin);
        //

        $user1 = new User();
        $user1->name = 'User';
        $user1->email = 'user@domain.com';
        $user1->password = bcrypt('user123'); 
        $user1->save();
        $user1->roles()->attach($role_user);
    }
     app/database/seeds/DatabaseSeeder.php
    
     $this->call(UsersTableSeeder::class)
     #7.7 - docker exec -it servidorweb php artisan migrate:refresh --seed

app/User.php

public function authorizeRoles($roles)
{
    abort_unless($this->hasAnyRole($roles), 401);    return true;
}public function hasAnyRole($roles)
{
    if (is_array($roles)) {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
    } else {
        if ($this->hasRole($roles)) {
             return true; 
        }   
    }    return false;
}public function hasRole($role)
{
    if ($this->roles()->where('name', $role)->first()) {
        return true;
    }    return false;
}

# agregamos rol por defecto al registrarse

app/Http/Controllers/Auth/RegisterController.php
use App\Role;

 $user =  ---- 
 
  $user->roles()->attach(Role::where('name', 'user')->first());       
  return $user;
  
  ##
  app/Http/Controllers/HomeController.php
  
   $request->user()->authorizeRoles(['user', 'admin']);
 
 
    #7.8 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Categoriaproductos -m
    
       $table->string('nombre');
       $table->string('descripcion')->nullable();
           docker exec -it servidorweb php artisan migrate --force   
    use App\Categoriaproductos;
    
     protected $primaryKey = 'id';

    protected $table = 'categoriaproductos';
    protected $fillable = ['nombre', 'descripcion'];
    
    #7.8.0.1 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model productos -m
    
      $table->integer('idcategoriaproductos');
            $table->string('nombre');
            $table->string('descripcion');
            $table->decimal('precio', 8, 2); 
    
    #7.8.1 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder CategoriasproductoTableSeeder
    
     for($i = 0; $i < 10; $i++)
        {
        $categoria = new Categoriaproductos();
        $categoria->nombre ="Categoria #" . $i;
        $categoria->descripcion =" Descripcion de la categoria #" . $i;
        $categoria->save(); 

            for($j = 0; $j < 20; $j++)
            {
                $producto = new Producto();
                $producto->idcategoriaproductos = $categoria->id;
                $producto->nombre ="Producto #". $j;
                $producto->descripcion =" Descripcion de la Producto #" . $j;
                $producto->precio = (($j * ($i + 1)) +  5000);
                $producto->save();
            }
        } 
    
    app/database/seeds/DatabaseSeeder.php
    
     $this->call(CategoriasproductoTableSeeder::class)
     
     docker exec -it servidorweb php artisan migrate:refresh --seed
     
    #7.9 - docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Producto -m
    
# -docker run --rm -it  -v $(pwd):/app laraveldeveloper php composer.phar --verbose install


##modificamos las rutas y separamos en admin y usuario normal
Route::group(['prefix' => 'administracion'], function() {
    require (__DIR__ . '/rutasdeadministradores.php');
});

Route::group(['prefix' => 'usuarios'], function() {
    require (__DIR__ . '/rutasdeusuarios.php');
});

##---creamos los archivos
rutasdeadministradores

Route::get('/categorias/inicio', 'CategoriasController@inicio')->name('listacategoria');

rutasdeusuarios
Route::get('/inicio', 'HomeController@usuarios')->name('iniciousuario');


   #ver- https://laravelcollective.com/docs/5.2/html
    #7.9.2 - docker run --rm -it  -v $(pwd):/app composer require "laravelcollective/html":"^5.2.0"
    
#docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:controller CategoriasController
#listamos
 public function inicio(Request $request)
    {
       
        $categorias = Categoriaproductos::paginate(5);
        //dd($categorias);
        return view('usuarios',[ "categorias" => $categorias ]);
    }
    
    https://github.com/alejofdezm/ucatolica/blob/master/lista.blade.php
    
###mostramos la vista de agregar
#agregamos la ruta de ir a la vista
Route::get('/categorias/agregar', 'CategoriasController@agregar')->name('agregarcategoria');

  public function agregar(Request $request)
    {
        $request->user()->authorizeRoles(['admin']); 
        return view('agregar');
    }
    
    https://github.com/alejofdezm/ucatolica/blob/master/agregar.blade.php
    
    #llamamos al metodo de guardar nuevo
    #agregamos la ruta para el post de agregar
    Route::post('/categorias/guardar', 'CategoriasController@guardar')->name('nuevacategoria');
    
    protected function validar(array $data)
    {
        return Validator::make($data, [
            'nombre' => 'required|string|max:35',
            'descripcion' => 'required|string|max:120'
        ]);
    }

    protected function crear(array $data)
    {
        $modeloagregar = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion']
        ];
         
        $categoria = Categoriaproductos::create($modeloagregar); 
        
        return $categoria; 
    }
    public function guardar(Request $request)
    {  
        $request->user()->authorizeRoles(['admin']);  

        $this->validar($request->all())->validate();

        $this->crear($request->all());

        return redirect(route('listacategoria'));
    }
    
    
    ####seguimos con eliminar
    
    
    
    https://github.com/alejofdezm/ucatolica/blob/master/editar.blade.php
    
    # -- https://laravel.com/docs/5.8/migrations
    # -- FontAwesome
    # -- docker exec -it servidorweb
    # -- docker exec -it servidorweb npm install @fortawesome/fontawesome-free --save
# docker-compose exec db bash

