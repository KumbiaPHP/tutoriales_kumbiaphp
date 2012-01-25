<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */
class VariablesController extends AppController {

    public function index() {
        
    }

    public function pasar_variable_vista() {
        //para pasar un dato del controlador a la vista
        //solo debe guardarse dicho dato en una variable de instancia del
        //controlador ( atributo ), y automaticamente esa variable se 
        //podrá usar en la vista y contendrá el dato que se le asignó
        //en el controlador.
        $this->nombre = "Manuel José"; //nombre es un atributo del controlador
        //que se está creando dinamicamente (no hay limites en la creacion de 
        //atributos dinamicos en los controladores).
        $this->fecha_de_hoy = date('d-m-Y'); //otra variable de instancia

        $variable_local = $this->nombre . ' ' . $this->fecha_de_hoy;
        //esta ultima variable ($variable_local) es local al metodo, es decir,
        //no podrá ser accedida fuera de este metodo, y desaparecerá
        //al terminar la ejecucion de esta acción.
    }

    public function pasar_parametros_vista($param_nombre, $edad) {
        //tambien es posible pasar los parametros que recibimos
        //en la acción a la vista.
        $this->nombre = $param_nombre;
        $this->edad = $edad;
        //solo debemos crear variables de instancia y pasarle los valores
        //de los parametros a las mismas.
    }

}
