# ucatolica
# Montamos el ambiente de desarrollo(optional en caso de montar el ambiente docker desde cero)

#---nos apoyamos del framework laravel 5.8 para conocer sobre POO, MVC y del mundo de desarrollo web 

        https://laravel.com/docs/5.8/installation

#--descargar laravel 5.8 desde aqui: https://github.com/laravel/laravel/archive/5.8.zip


(https://github.com/laravel/laravel/tree/5.8)


#--descomprimir laravel 5.8, cambiar nombre y poner el que gusten, "ejemplo claseu".

#--descargar del siguiente link archivos a descomprimir: https://github.com/alejofdezm/ucatolica/raw/master/descomprimir.zip

#--descomprimir el archivo con nombre descomprimir.zip dentro de la carperta de laravel anterior "ejemplo: claseu".

#--Siempre que se quieran volver super usuario, deben de ejecutar: sudo su y luego su contraseña.

#--en caso de montar el ambiente desde cero, compilar el servidor web de docker con el siguiente comando: 

        docker build -t laraveldeveloper ./  
        
        (paso opcional, solo si es nuevo el ambiente, en las maquinas virtual entregadas no hay que hacer esto, solo si quieren montar su propia maquina virtual o local)

        luego ejecutamos el siguiente comando (opcional): docker pull composer (instala contenedor de composer)

## --visto en clases(levantar su ambiente en clase)

#-- abrimos visual studio code (editor que usaremos en clases)

#-- abrimos la carpeta con visual code donde descomprimimos laravel "ejemplo claseu" y donde alojamos los archivos que vienen comprimidas en el archivo descomprimir.zip 

#-- luego abrimos la terminal

#-- se vuelven super usuario: sudo su

#-- levantan el ambiente de desarrollo con el siguiente comando:

     docker-compose up -d
 
 #- el comando anterior nos lenvata lo siguiente:
 
        #- servidor web en el puerto 80, ruta de consulta: http://127.0.0.1/
  
        #- servidor de mysql con nombre: db
  
        #- servidor de administración de sus ambientes (portainer): http://127.0.0.1:9000/
               **-- Datos de ingresos
               -- los definen los desarrolladores
        #- servidor para administrar mysql por medio de phpmyadmin: http://127.0.0.1:8081/
               **--Datos de ingreso:
               -- servidor: db 
               -- usuario: root
               -- contraseña: M4r1adb001.
        
        #- servidor dummy para correo electronico en la siguiente dirección: http://127.0.0.1:8082/
               **--Datos de ingreso:
               -- usuario: admincorreo
               -- contraseña: Admin123*
  
#instalamos las dependencias que necesita laravel para poder funcionar de forma basica:

     docker run --rm -it  -v $(pwd):/app composer composer install 

#-- luego de cada comando ejecutado que agregue nuevos archivos, es bueno dar permisos de lectura y escritura a toda la carpeta (el comando anterior agrego nuevos archivos que son las dependecias básicas):
     
     chmod -R 777 ./
     
#-- ingresar a la carpeta del proyecto y renombrar el archivo con nombre: *.env.example* por *.env* "remover example"
     
     remplazamos los siguientes datos del nuevo archivo .env:
     
        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=laravelcurso001
        DB_USERNAME=root
        DB_PASSWORD=M4r1adb001.

#-- generamos el key de cache, con el siguiente comando:

        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan key:generate

        esto modifica en el archivo .env la variable: APP_KEY= 

#-- incorporar los archivos de configuracion en el cache, para obtener una lectura rapida:
        
         docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan config:cache

#-- activamos la autenticación de laravel de caja:
        
        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:auth

#-- migramos las tablas de autenticación, crea 3 tablas:

        docker exec -it servidorweb php artisan migrate --force
        
#-- modifican el archivo: app/database/seeds/DatabaseSeeder.php e incorporan en la funcion run:

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@domain.com';
        $user->password = bcrypt('admin123'); 
        $user->save();
        
        $user1 = new User();
        $user1->name = 'User';
        $user1->email = 'user@domain.com';
        $user1->password = bcrypt('user123'); 
        $user1->save();
        
         ademas hay que indicar que se require usar el modelo de usuario en la clase DatabaseSeeder, por medio del use en la parte superior del archivo despues de <?php :
                
                use App\User;
                

#-- paso opcional (3% extras de la nota, a quien logre ejecutar la siguiente guia y hacer que los roles entren a funcionar, 2% extras quien lo explique a la clase de forma formal, antes de que lo explique el profesor "reto")
        
        seguir guia: https://medium.com/@cvallejo/roles-usuarios-laravel-2e1c6123ad
        
        comandos de la guia adaptados a docker:
        
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Role -m
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:migration create_role_user_table
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder RoleTableSeeder
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder UsersTableSeeder
        
##ignorar este codigo
        #--2 - docker exec -it servidorweb  bash
        #---2.1 - sudo chown -R $USER:$USER ./
        #---2.5 - sudo chown -R www-data:www-data ./
        #---4 - docker ps   
        #6 - docker-compose exec laraveldeveloper nano .env (optional)


    
    
     
    #7.5 - 
    
    app/database/seeds/UsersTableSeeder.php
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_user = Role::where('name', 'user')->first();

        
        $user->roles()->attach($role_admin);
        //

        
        $user1->roles()->attach($role_user);
    }
     app/database/seeds/DatabaseSeeder.php
    
     $this->call(UsersTableSeeder::class)
     #7.7 - docker exec -it servidorweb php artisan migrate:refresh --seed


 
 
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

