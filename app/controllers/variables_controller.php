<?php

class VariablesController extends AppController {

    public function index() {
        
    }

    public function pasar_variable_vista() {
        //para pasar un dato del controlador a la vista
        //solo debe guardarse dicho dato en una variable de instancia del
        //controlador ( atributo ), y automaticamente esa variable se 
        //podrá usar en la vista y contendrá el dato que se le asignó
        //en el controlador.
        $this->nombre = 'Manuel José'; //nombre es un atributo del controlador
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
    
    public function cargando_datos_form_1(){
        //en la vista tenemos un formulario con dos cajas
        //de texto y un select.
        //la primera jaca de texto tiene como atributo name="nombres"
        //la segunda es edad y el select se llama sexo.
        //al pasar variables desde los controladores a la vista
        //con nombres de variable igual a algun atributo name de algun input
        //ó select u otro, estos se cargan con el valor de dicha variable.
        $this->nombres = 'Manuel Aguirre'; //en la vista hay un input de tipo texto con
        // name="nombres" , y desde el controlador le estamos dando valor a dicho input
        $this->edad = 23; //lo mismo para el input con name="edad"
        $this->sexo = 'M'; //una de las opciones del select con name="sexo"
        //tiene como valor 'M', y esa será la opcion que aparecerá seleccionada 
        //al mostrar la vista en el navegador 
    }
    
    public function cargando_datos_form_2(){
        // otra manera de enviar datos a los formularios es a travez de arreglos,
        //donde las posiciones del arreglo representan los campos del 
        //formulario ( cajas de texto, selects, textarea, checks, radios, etc )
        //dichos campos tienen los atributos name de la siguiente manera:
        //<input name="form[nombres]" ... />
        //<select name="form[sexo]" ... />, etc.
        $this->form['nombres'] = 'Manuel Aguirre';
        $this->form['edad'] = 23;
        $this->form['sexo'] = 'M';
        
        $this->otro_form = array(
            'nombres' => 'Carmen Maria',
            'edad' => 30,
            'sexo' => 'F'
        );
    }
    
    public function cargando_datos_form_3(){
        //ademas de arreglos se le pueden pasar objetos,
        //donde los atributos ó variables de instancia del mismo
        //representan los campos del formulario.
        //<input name="form[nombres]" ... />
        //<select name="form[sexo]" ... />, etc.
        $this->form->nombres = 'Manuel Aguirre';
        $this->form->edad = 23;
        $this->form->sexo = 'M';
    }

}
