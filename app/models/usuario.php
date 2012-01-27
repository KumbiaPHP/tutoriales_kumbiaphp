<?php

class Usuario extends ActiveRecord {

    protected function initialize() {
        $this->validates_presence_of('login', 'message: Debe Especificar un Login');
        $this->validates_presence_of('clave', 'message: Debe Escribir una clave');
        $this->validates_presence_of('clave2', 'message: Debe Repetir la clave');
        $this->validates_length_of('clave', 10, 3);
        $this->validates_uniqueness_of('login','message: Este Login ya esta siendo usado en el Sistema');
    }

    protected function claves_iguales($clave1, $clave2) {
        return $clave1 === $clave2;
    }

    protected function before_validation_on_create() {
        if (!$this->claves_iguales($this->clave, $this->clave2)) {
            Flash::error('Las Claves no Coinciden');
            return 'cancel'; //retornamos el string 'cancel' para que 
            //active record no continue con el guardado.
        }
    }

    protected function before_create() {
        //ejemplo de uso de los callback en active record
        //podemos obtener los valores de los campos simplemente 
        //accediendo a los mismos como atributos del modelo (clase).
        $this->clave = md5($this->clave);
        $this->activo = '1';
    }

    protected function after_create() {
        Flash::valid("El Usuario $this->nombre fué creado exitosamente...!!!");
    }

}

