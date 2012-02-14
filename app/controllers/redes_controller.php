<?php

/**
 * Controlador para integrar Twitter a KumbiapHP 
 * Recomiendo leer la documentación disponible en http://dev.twitter.com
 * y la libreria que usamos aquí en http://github.com/themattharris/tmhOAuth
 * Fecha 05/02/2012
 *
 * @author Jaro Marval (jamp) <jampgold@gmail.com>
 * @version 0.1
 */

class RedesController extends AppController {

    /**
     * Aqui verificamos la existencia de una identidad en base de datos, sino
     * procedemos a capturar una, todo esto basado en los ejemplos de la libreria
     * de Sr. Matt Harris en sus librerias
     * 
     */
    public function index() {
        // Cargamos modelo para obtener datos de la BBDD
        $red = Load::model('usuarios');

        // Leemos del Archivo de configuración el consumerkey y el consumersecret
        // de nuestra aplicación twitter
        $config = Config::read('apis', TRUE);
        $consumer_key = $config['twitter']['consumerkey'];
        $consumer_secret = $config['twitter']['consumersecret'];

        // Verificamos que tengamos una sesión guardada de usuario Twitter
        $this->twitterdata = $red->gettwitter();

        // Verificamos que no existe ninguna sesión guardada
        if (!$this->twitterdata) {

            // Cargamos las librerias necesarias para nuestra aplicación
            Load::lib('apis/twitter/tmhOAuth');
            Load::lib('apis/twitter/tmhUtilities');

            // Inicializamos la libreria de Twitter
            $tmhOAuth = new tmhOAuth(array(
                        'consumer_key' => $consumer_key,
                        'consumer_secret' => $consumer_secret,
                    ));

            // Definimos el sitio donde estamos usando nuestro código para luego
            // volver después de que iniciemos sesión de nuestro usuario
            $here = tmhUtilities::php_self();

            // Función para manejar los errores devueltos durante la autenticación
            // en Twitter
            function outputError($tmhOAuth) {
                echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
                tmhUtilities::pr($tmhOAuth);
            }

            // Verificamos que las variables de acceso existan
            if (isset($_SESSION['access_token'])) {

                // Recogemos las sessión de Twitter y almacenamos en BBDD el
                // token y el token_secret del usuario autenticado
                $red->setTwitter($_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);

                // Destruimos la variable de sesión
                unset($_SESSION['access_token']);

                // Redireccionamos al página de donde originalmente veniamos
                header("Location: {$here}");

            // Verficamos que la autenticación se halla llevado a cabo
            } elseif (isset($_REQUEST['oauth_verifier'])) {
                
                // Parametrizamos el user_token y el user_secret desde la sesión
                $tmhOAuth->config['user_token'] = $_SESSION['oauth']['oauth_token'];
                $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

                // Verificamos que el user_token y el user_secret sean correctos mediante oauth_verifier
                $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
                    'oauth_verifier' => $_REQUEST['oauth_verifier']
                        ));

                // Si el user_token y user_secret son correctos y refrescamos la página
                if ($code == 200) {
                    $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                    unset($_SESSION['oauth']);
                    header("Location: {$here}");
                    
                // Sino mostramos el error que no retorna
                } else {
                    outputError($tmhOAuth);
                }
            
            // Si no existe ninguna identidad guardada
            } else {
                
                // Parametrizamos el tipo de acceso de nuestra aplicacion
                $params = array(
                    'oauth_callback' => $here,
                    'x_auth_access_type' => 'write'
                );
                
                // Solicitamos a los servidores de Twitter un link valido para 
                // que nuestra aplicación tenga permiso sobre la identidad del 
                // usuario
                $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);
                
                // Si la solicitud es exitosa nos devuelve los parametros 
                // para generar nuestro link
                if ($code == 200) {
                    $_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
                    $this->Twitter = $tmhOAuth->url("oauth/authorize", '') . "?oauth_token={$_SESSION['oauth']['oauth_token']}";
                    
                // Sino mostramos el error
                } else {
                    outputError($tmhOAuth);
                }
            }
            
        // Si poseemos poseemos una identidad Twitter asociada
        } else {
            
            // Cargamos las librerias necesarias para trabajar con Twitter
            Load::lib('apis/twitter/tmhOAuth');
            Load::lib('apis/twitter/tmhUtilities');
            
            // Parametrizamos con la identidad que tenemos guardada
            $user = $this->twitterdata;
            $tmhOAuth = new tmhOAuth(array(
                        'consumer_key' => $consumer_key,
                        'consumer_secret' => $consumer_secret,
                        'user_token' => $user['token'],
                        'user_secret' => $user['secret'],
                    ));
            
            // Auteticamos la identidad
            $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
            
            // Si la autenticacion es correcta extraemos el avatar y el link de 
            // identidad guardada
            if ($code == 200) {
                $response = json_decode($tmhOAuth->response['response']);
                $this->twitterdata['image'] = $response->profile_image_url;
                $this->twitterdata['profile'] = 'http://www.twitter.com/' . $response->screen_name;
                
            // Sino es correcta mostramos el error de autenticacion
            } else {
                tmhUtilities::pr(htmlentities($tmhOAuth->response['response']));
            }
        }
    }

    /**
     * Controlador para publicar nuestros Tweet con la identidad que ya tenemos 
     * almacenada
     * 
     */
    public function tweet() {

        if (Input::hasPost('Publicar')) {

            // Extraemos todos los parametros necesarios para poder publicar 
            // nuestro Tweet
            $config = Config::read('apis', TRUE);
            $consumer_key = $config['twitter']['consumerkey'];
            $consumer_secret = $config['twitter']['consumersecret'];
            $user = Load::model('usuarios')->gettwitter();
            $tweet = Input::post('tweet');

            // Cargamos las librerias necesarias
            Load::lib('apis/twitter/tmhOAuth');
            Load::lib('apis/twitter/tmhUtilities');

            // Parametrizamos la identidad que tenemos almacenada
            $tmhOAuth = new tmhOAuth(array(
                        'consumer_key' => $consumer_key,
                        'consumer_secret' => $consumer_secret,
                        'user_token' => $user['token'],
                        'user_secret' => $user['secret'],
                    ));

            // Posteamos nuestro tweet
            $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
                'status' => $tweet
                    ));

            // Creo que esto no ni necesario de explicar
            if ($code == 200) {
                Flash::success("Tweet Publicado");
            } else {
                Flash::error(htmlentities($tmhOAuth->response['response']));
            }
        }
    }

    /**
     * Esto no es nada de otro mundo solo es para borrar la identidad que 
     * tenemos guardada en nuestra BBDD, tampoco hay mucho que explicar aqui
     * 
     */
    public function desasociar() {
        Load::model('usuarios')->setTwitter();
        Router::redirect('redes/');
    }

}

?>
