<?php

class Modelos2Controller extends AppController {

    public function index() {
        
    }
    
    public function find(){
        /**
         * el metodo find devuelve siempre un array de objetos active record
         * @example:
         * 
         * array(
         *      0 => $modelo_registro_0,
         *      1 => $modelo_registro_1,
         *      2 => $modelo_registro_2,
         *      ....
         *      n => $modelo_registro_n,
         * )
         * 
         * Cuando se le pasa un numero como unico parametro al find,
         * este busca por la clave primaria y
         * no es un arreglo lo que el metodo devuelve, sino el objeto como tal,
         * es lo mismo que hacer un find_first, aunque es mejor usar este ultimo
         * al saber que se va a devolver un unico registro.
         * 
         */
        $this->resultado = Load::model('menu')->find();
    }

    public function find_first(){
        /**
         * El metodo find_first devulve el primer registro encontrado en la
         * bd que cumpla con las condiciones pasadas como parametro,
         * generalmente se le pasa un numero entero que representa el id del
         * registro que se desea devolver, en el ejemplo no se le está pasando
         * ningun parametro, por lo tanto el metodo devolverá el primer
         * registro que consiga en la tabla.
         */
        $this->resultado = Load::model('menu')->find_first();
        /**
         * Otra manera de llamar al metodo find_first().
         *
         * En este ejemplo si se pasa un parametro que es el id del
         * usuario que se quiere devolver.
         */
        Load::models('usuario');
        $usr = new Usuario();
        $this->usuario =$usr->find_first(1);

        /**
         * Ahora si le pasamos una condicion al metodo.
         * Devolverá el ultimo registro activo que encuentre en la bd
         */
        $menu = Load::model('menu');
        $this->menu = $menu->find_first('activo = 1','order: id DESC');

    }
}
