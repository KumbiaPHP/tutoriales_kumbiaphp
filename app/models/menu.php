<?php

class Menu extends ActiveRecord {

    //metodo equivalente al constructor de la clase, es llamado en el
    //constructor padre
    public function initialize(){
        //por defecto el active record va a validar los campos que sean
        //NOT NULL en la tabla de la base de datos
        //En este caso la tabla menu tiene 2 campos not null:
        //los campos nombre,url (la clave primaria no se toma en cuenta)
        //sin embargo para efectos del ejemplo se hará uso de las validaciones
        //de active record para cambiar los mensajes a mostrar en caso de
        //error en la validacion de los campos.
        $this->validates_presence_of('nombre','message: Debe Escribir un Nombre para el Menú...!!!');
        $this->validates_presence_of('url','message: Debe Escribir una URL para el Menú...!!!');
        $this->validates_uniqueness_of('nombre','message: Este nombre de menú ya existe en el sistema');
    }

    
}
