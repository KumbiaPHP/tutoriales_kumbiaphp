<?php

Load::models('menu');

class ModelosController extends AppController {

    public function index() {
        
    }
    
    public function usos_callback_1(){
        if ( Input::hasPost('array_formulario') ){
            $model = new Menu(Input::post('array_formulario'));

            if ( $model->save() ){
                Flash::valid("El menu $model->nombre fué creado exitosamente...!!!");
                Input::delete('array_formulario');
            }else{
                Flash::error('No se Han podido Guardar los datos...!!!');
            }
        }
    }

}
