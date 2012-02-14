<?php
/**
 * Modelo para manejar almacenar y obtener el user_token y el user_secret 
 * del usuario de Twitter
 * Fecha 05/02/2012
 *
 * @author Jaro Marval (jamp) <jampgold@gmail.com>
 * @version 0.1
 */

/**
 * Modelo con 2 metodos uno para obtener una identidad guardada en BBDD y otra 
 * para guardar una identidad, "simple"
 */
class usuarios extends ActiveRecord {

    function getTwitter() {
        $r = $this->find('1');
        
        $o = array(
            'token' => $r->user_token,
            'secret' => $r->user_secret,
        );
        
        if ($r->user_token == '') {
            return FALSE;
        } else {
            return $o;
        }
        
    }
    
    public function setTwitter($id = '', $secret = '') {
        $r = $this->find('1');
        $r->user_token = $id;
        $r->user_secret = $secret;
        
        if ( $r->save() ) {
            return True;
        }
    }
    
}

?>
