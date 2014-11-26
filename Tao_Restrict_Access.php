<?php

/*
Plugin Name: Tao Restrict Access
Plugin URI: http://www.bindigital.com.br
Description: Tao Restrict Access BinDigital
Version: 1.0
Author: Tiago Pires
Author URI: http://www.bindigital.com.br
*/

class Tao_Restrict_Access
{

	// Checa se Existe Página Inicial da Área Restrita
	public static function install()
    {
    	global $wpdb;
		$pageRel = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_name='area-restrita'", ARRAY_N);
		if(empty($pageRel)){
			$arg = array(
				'post_name'=>'area-restrita',
				'post_title' => 'Área Restrita',
				'post_status' => 'private',
				'post_type' => 'page',
				'menu_order' => 100,
				'post_content' => ''
			);
			wp_insert_post( $arg );
		}
    }

    // Checa se Existe Página Inicial da Área Restrita e Deleta
	public static function uninstall()
    {
    	global $wpdb;
		$pageRel = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_name='area-restrita'", ARRAY_N);

		if(!empty($pageRel)){
			$pageRel = $pageRel[0][0];
			$wpdb->query("DELETE FROM $wpdb->posts WHERE ID = $pageRel");
		}
    }

	// Script and Call
	public static function callall()
	{
		wp_enqueue_script( "ajax_loggin", plugin_dir_url( __FILE__ ) . "html/js/ajax.js", array('jquery') );
		wp_localize_script( "ajax_loggin", "ajax_loggin_tao", array( 
			"loggin" => admin_url( "admin-ajax.php?action=ajax_loggin" ),
			"url" => get_home_url()."/area-restrita",
		));
	}

	// Form Access Loggin
	public static function form_access()
    {
    	if(!is_user_logged_in()) :

    		include_once( dirname(__FILE__) . '/html/form_access.php');

    	else :

    		echo '<a href="'. get_home_url() .'/area-restrita/" class="access_restrict">Acessar Área Restrita</a>';
    		echo '<a href="'. esc_url( wp_logout_url( get_home_url() ) ) .'" title="Sair" class="logout">Sair</a>';

    	endif;
    }

    public static function ajax_loggin()
    {

		if (!$_POST['user']) {
			echo 'Necessário inserir usuário.';
			exit;
		}
		if (!$_POST['pass']) {
			echo 'Necessário inserir senha.';
			exit;
		}

		$user = wp_authenticate($_POST['user'], $_POST['pass']);

		if (isset($user->errors)) {

			if(isset($user->errors["invalid_username"])) echo $user->errors["invalid_username"][0];
			if(isset($user->errors["incorrect_password"])) echo $user->errors["incorrect_password"][0];
		
		} else {

			wp_set_auth_cookie($user->ID);
			echo "auth";

		}

		exit;
		
    }

    public static function the_title_trim($title) 
    {
		$title = esc_attr($title);
		$findthese = array(
		    '#Protegido:#',
		    '#Privado:#'
		);
		$replacewith = array(
		    '', // Se quiser, escreva o que quer usar em vez de "Protegido:"
		    '' // e quiser, escreva o que quer usar em vez de "Privado"
		);
		$title = preg_replace($findthese, $replacewith, $title);
		return $title;
	}

}


// Inserir em uma página o seguinte código shortcode [tao-restrict-access] para que seja exibido o formulário de acesso
add_shortcode('tao-restrict-access', array( 'Tao_Restrict_Access', 'form_access' ));

// Todas as chamadas scripts e styles
add_action( 'wp_enqueue_scripts', array( 'Tao_Restrict_Access', 'callall' ));

// Acesso Ajax Loggin
add_action('wp_ajax_nopriv_ajax_loggin', array('Tao_Restrict_Access', 'ajax_loggin'));
add_action('wp_ajax_ajax_loggin', array('Tao_Restrict_Access', 'ajax_loggin'));

// Remove título privado
add_filter('the_title', array('Tao_Restrict_Access', 'the_title_trim'));

// Chama Método Instalação
register_activation_hook(dirname(__FILE__) . DIRECTORY_SEPARATOR . basename(__FILE__), array('Tao_Restrict_Access', 'install'));

// Chama Método de Desinstalação
register_deactivation_hook(dirname(__FILE__) . DIRECTORY_SEPARATOR . basename(__FILE__), array('Tao_Restrict_Access', 'uninstall'));