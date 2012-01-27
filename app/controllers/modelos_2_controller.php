<?php

class Modelos_2Controller extends AppController {

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
}
