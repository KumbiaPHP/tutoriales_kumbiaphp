<?php
//con este metodo de la clase model podemos cargar varios modelos para
//usarlos en el controlador
Load::models('menu','usuario');

class ModelosController extends AppController {

    public function index() {
        
    }
    
    public function validaciones_1(){
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
    public function validaciones_2_callback(){
        if ( Input::hasPost('form') ){
            $model = new Usuario(Input::post('form'));

            if ( $model->save() ){
                Input::delete('form');
            }else{
                Flash::error('No se Han podido Guardar los datos...!!!');
            }
        }
    }

}
