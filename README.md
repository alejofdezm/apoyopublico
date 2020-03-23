# ucatolica
# Montamos el ambiente de desarrollo(optional en caso de montar el ambiente docker desde cero)

#1---nos apoyamos del framework laravel 5.8 para conocer sobre POO, MVC y del mundo de desarrollo web 

        https://laravel.com/docs/5.8/installation

#1.1--descargar laravel 5.8 desde aqui: https://github.com/laravel/laravel/archive/5.8.zip


(https://github.com/laravel/laravel/tree/5.8)


#1.2--descomprimir laravel 5.8, cambiar nombre y poner el que gusten, "ejemplo claseu".

#1.3--descargar del siguiente link el archivos a descomprimir: https://github.com/alejofdezm/ucatolica/raw/master/descomprimir.zip

#1.4--descomprimir el archivo con nombre descomprimir.zip dentro de la carperta de laravel anterior "ejemplo: claseu".

#1.5--Siempre que se quieran volver super usuario, deben de ejecutar: sudo su y luego su contraseña.

#1.6--en caso de montar el ambiente desde cero, compilar el servidor web de docker con el siguiente comando: 

        docker build -t laraveldeveloper ./  
        
        (paso opcional, solo si es nuevo el ambiente, en las maquinas virtual entregadas no hay que hacer esto, solo si quieren montar su propia maquina virtual o local)

        luego ejecutamos el siguiente comando (opcional): docker pull composer (instala contenedor de composer)

## --visto en clases(levantar su ambiente en clase)

#2.-- abrimos visual studio code (editor que usaremos en clases)

#2.1-- abrimos la carpeta con visual code donde descomprimimos laravel "ejemplo claseu" y donde alojamos los archivos que vienen comprimidas en el archivo descomprimir.zip 

#2.2-- luego abrimos la terminal

#2.3-- se vuelven super usuario: sudo su

#2.4-- levantan el ambiente de desarrollo con el siguiente comando:

     docker-compose up -d
 
 #2.4.1- el comando anterior nos lenvata lo siguiente:
 
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
  
#2.5instalamos las dependencias que necesita laravel para poder funcionar de forma basica:

     docker run --rm -it  -v $(pwd):/app composer composer install 

#000-- luego de cada comando ejecutado que agregue nuevos archivos, es bueno dar permisos de lectura y escritura a toda la carpeta (el comando anterior agrego nuevos archivos que son las dependecias básicas):
     
     chmod -R 777 ./
     
#2.6-- ingresar a la carpeta del proyecto y renombrar el archivo con nombre: *.env.example* por *.env* "remover example"
     
     remplazamos los siguientes datos del nuevo archivo .env:
     
        DB_CONNECTION=mysql
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=laravelcurso001
        DB_USERNAME=root
        DB_PASSWORD=M4r1adb001.

#2.6.1-- generamos el key de cache, con el siguiente comando:

        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan key:generate

        esto modifica en el archivo .env la variable: APP_KEY= 

#2.6.2-- incorporar los archivos de configuracion en el cache, para obtener una lectura rapida:
        
         docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan config:cache

#2.7-- activamos la autenticación de laravel de caja:
        
        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:auth

#2.7.1-- migramos las tablas de autenticación, crea 3 tablas:

        docker exec -it servidorweb php artisan migrate --force
        
#2.7.2-- modifican el archivo: database/seeds/DatabaseSeeder.php e incorporan en la funcion run "esto nos creara automaticamente dos usuarios en base de datos, pueden incluir mas si gustan":

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
             
             
#2.7.3-- migramos los datos del seeder (punto anterior):
        
           docker exec -it servidorweb php artisan migrate:refresh --seed

#2.7.4 manejo de Roles

usar siguiente guia: https://medium.com/@cvallejo/roles-usuarios-laravel-2e1c6123ad


#2.8-- vamos a crear dos tablas, para almacenar categoria de productos y productos

        Categoriaproductos 
                -- id
                -- nombre
                -- descripcion
                
        Productos 
                -- id
                -- idcategoriaproductos
                -- nombre
                -- descripcion
#2.8.1.1- creamos el modelo y archivo de migración de tabla Categoriaproductos

        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Categoriaproductos -m
        
        Nos genera dos archivo, 
        
        1- en la carpeta App, nombre de la clase (modelo): Categoriaproductos
        
        Añadimos los nombres de las columnas de la tabla de base de datos y nombre de la tabla e indicamos cual es la columna inidice (investigar que es una columna indice en una tabla de base de datos):
                Añadir siguientes lineas al modelo Categoriaproductos:
                
                        protected $primaryKey = 'id'; 
                        protected $table = 'categoriaproductos';
                        protected $fillable = ['nombre', 'descripcion'];
        
        2- en la carpeta database / migrations / se genera un archivo con nombre que finaliza con: ---- _create_categoriaproductos_table.php
    
        Añadimos:
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('descripcion')->nullable();;
            $table->timestamps();

#2.8.1.2- creamos el modelo y archivo de migración de tabla Productos

        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Productos -m
        
        Nos genera nuevamente dos archivo, 
        
        1- en la carpeta App, nombre de la clase (modelo): Producto
        
        Añadimos los nombres de las columnas de la tabla de base de datos y nombre de la tabla e indicamos cual es la columna inidice (investigar que es una columna indice en una tabla de base de datos):
                Añadir siguientes lineas al modelo producto:
                
                        protected $primaryKey = 'id'; 
                        protected $table = 'productos'; 
        
        2- en la carpeta database / migrations / se genera un archivo con nombre que finaliza con: ---- _create_productos_table.php
    
        Añadimos:
            $table->bigIncrements('id');
            $table->integer('idcategoriaproductos');
            $table->string('nombre');
            $table->string('descripcion');
            $table->decimal('precio', 8, 2); 
            $table->timestamps(); 


#2.8.2migramos las nuevas tablas:

        docker exec -it servidorweb php artisan migrate --force   
    
#3. Vamos a cargar nuestras dos tablas con datos de ejemplo, para ello primero vamos a correr este comando:  

        docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder CategoriasproductoTableSeeder
        
        Esto nos generara un archivo en database / seeds, de mombre: CategoriasproductoTableSeeder
        
#3.1 ingresar al archivo generado con el comando anterior y indicar que van hacer uso de los dos modelos que representan sus tablas creadas anteriormente:
    
    <?php
    
    use App\Categoriaproductos;
    use App\Productos;
 
#3.2 luego en el metodo run(), debemos de indicar que ejecute el siguiente codigo:
    
    
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
    
    Nota: este codigo lo que hace es ingresar 10 categorias y por cada categoria agregara 20 productos.
    
#3.2.1 luego en la ruta:  database / seeds en el archivo DatabaseSeeder.php, al final del metodo run, agregamos la siguiente linea, que invoca al codigo anterior:
    
     $this->call(CategoriasproductoTableSeeder::class);
     
#3.2.2 por ultimo, hacemos la migración de los datos, ejecutando nuevamente el comando:
     
     docker exec -it servidorweb php artisan migrate:refresh --seed
     
#  Vamos a crear una ruta, para mostrar los datos de categorias. 

#4. modificamos el archivo que se encuentra en: routes / , llamado web.php

        Objetivo: permitir que cuando el usuario escriba la ruta de nuestro sitio, ejemplo: miu.com, pueda ver las categorias si ingresa a la siguiente ruta: http(s)://miu.com/categorias/lista, en nuestro caso: http:/127.0.0.1/categorias/lista

       Para ello vamos a ir al archivo y agregar la siguiente linea: 
       
       Route::get('/categorias/lista', 'HomeController@listarcategorias')->name('listarcategorias');
       
       Nota: esta linea lo que indica es, que cuando el usuario escriba esa ruta, sera digido al controllador que se llama HomeController a la acción (funcion) llamada listarcategorias
       
       Nota: 1- recuerden que acción y funcion es lo mismo, pero en este caso para los controller las llamaremos acciones.
             2- en los controladores vamos agregar lo que se llama, logica de negocio.
    
#4.1 creamos nuestra nueva acción en el controller Home para poder listar todas las categorias
        
        1- agregan luego de la creacion del namespace: 
                        use App\Categoriaproductos;
                  con el fin de poder hacer uso del modelo que hace referencia a nuestra tabla de base de datos.
        2- dentro de la implementación de la clase homecontroller, agregamos un metodo más que llame segun lo definieron en el Router, en nuestro caso de ejemplo: listarcategorias y hacemos uso de dd, para mostrar los datos que estan almacenado en base de datos.
        
        de esta forma, rotorna todas las categorias:
        
         public function listarcategorias()
            {
                dd(Categoriaproductos::get());
            }
        
        de esta otra forma, retorna solo la categoria del id que se le pase (en este caso de ejemplo, solo la categoria con id=3):
        
        public function listarcategorias()
            {
                dd(Categoriaproductos::where('id', 3 )->get());
            }
            
            
            
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
 
 
 #-- paso opcional (3% extras de la nota, a quien logre ejecutar la siguiente guia y hacer que los roles entren a funcionar, 2% extras quien lo explique a la clase de forma formal, antes de que lo explique el profesor "reto")
        
        seguir guia: https://medium.com/@cvallejo/roles-usuarios-laravel-2e1c6123ad
        
        comandos de la guia adaptados a docker:
        
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:model Role -m
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:migration create_role_user_table
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder RoleTableSeeder
        #- docker run --rm -it  -v $(pwd):/app laraveldeveloper php artisan make:seeder UsersTableSeeder
        
        

# -- https://laravel.com/docs/5.8/migrations
    # -- FontAwesome
    # -- docker exec -it servidorweb
    # -- docker exec -it servidorweb npm install @fortawesome/fontawesome-free --save
# docker-compose exec db bash


## ignorar este codigo
        # -docker run --rm -it  -v $(pwd):/app laraveldeveloper php composer.phar --verbose install
        #--2 - docker exec -it servidorweb  bash
        #---2.1 - sudo chown -R $USER:$USER ./
        #---2.5 - sudo chown -R www-data:www-data ./
        #---4 - docker ps   
        #6 - docker-compose exec laraveldeveloper nano .env (optional)
