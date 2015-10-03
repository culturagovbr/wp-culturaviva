<?php

/*
 Plugin Name: CSV api
 Plugin URI: 
 Description: Api para acesso a dados importados via CSV
 Version: 0.1.0
 Author: Jacson Passold
 Author URI: https://github.com/jacsonp
 */

class CSVApi
{
	protected $_table_name = 'csvapi';
	protected $logfilename = 'csv_import.log';
	protected $_cols = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af','ag','ah','ai','aj','ak','al','am','an','ao','ap','aq','ar','as','at','au','av','aw','ax','ay','az');
	
	public function __construct()
	{
		add_action('init', array($this, 'init'));
		add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'wp_ajax_nopriv_get_cultura', array($this, 'ajaxget_cultura'));
		add_action( 'wp_ajax_get_cultura', array($this, 'ajaxget_cultura'));
		
		
		$dir = wp_upload_dir();
		$this->logfilename = $dir['basedir']."/logs/".$this->logfilename;
	}
	
	public function init()
	{
		$this->check_database();
	}
	
	function check_database()
	{
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->_table_name;
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name) {
			$this->create_table();
		}
	}
	
	function create_table()
	{
		global $wpdb;
		$table_name = $wpdb->base_prefix . $this->_table_name;
	
		if (!empty ($wpdb->charset))
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
			$charset_collate .= " COLLATE {$wpdb->collate}";
	
		$sql = "CREATE TABLE {$table_name} (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,";
		foreach ($this->_cols as $col)
		{
			$sql .= " `$col` varchar(300) null,";
		}
		$sql .= "	
			UNIQUE KEY id (id)
		) {$charset_collate};
		";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	/**
	 * Add options page
	 */
	public function add_theme_page()
	{
		// This page will be under "Settings"
		$page_hook = add_management_page(
				__('Importar do arquivo','redeculturaviva'),
				__('Importar do arquivo','redeculturaviva'),
				'import',
				'redeculturaviva-import-file',
				array(&$this, 'create_admin_page')
		);
		//add_action('load-' . $page_hook, array(&$this, 'admin_load'));
	
	}
	
	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'redeculturaviva_theme_options', array() );
		?>
		<div class="wrap">
			<h2><?php _e('Import File Tool', 'redeculturaviva') ?></h2>
			<form id="importcsv-form" method="post" action="options.php">
			            <?php
			                // This prints out all hidden setting fields
			                settings_fields( 'redeculturaviva_option_group' );   
			                do_settings_sections( 'redeculturaviva-import-file' );
			                ?>
			                	<input type="file" name="media-importcsv-filename" id="media-importcsv-filename" class="options-file-upload" >
			                <?php
			                submit_button("Importar Csv", 'secundary', 'importcsv' );
			                submit_button(); 
			            ?>
			            </form>
			<div id="result"></div>
		</div>
		<?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'redeculturaviva_option_group', // Option group
            'redeculturaviva_theme_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );
        
		if(array_key_exists('page', $_REQUEST) && $_REQUEST['page'] == 'redeculturaviva-import-file')
		{
			wp_register_script('redeculturaviva_import_scripts', plugin_dir_url( __FILE__ ) . '/assets/js/redeculturaviva_import_scripts.js', array('jquery'));
			
			wp_enqueue_script('redeculturaviva_import_scripts');
					
			wp_localize_script( 'redeculturaviva_import_scripts', 'redeculturaviva_import_scripts_object',
			array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
		}
		add_action( 'wp_ajax_ImportarCsv', array($this, 'ImportarCsv_callback') );
		
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        /*if( array_key_exists('criar_estatos_cidades', $input ))
        {
			$new_input['criar_estatos_cidades'] = $input['criar_estatos_cidades'] == 'S'? 'S' : 'N';
        
			if($new_input['criar_estatos_cidades'] == 'S')
			{
				if(!is_array($this->options))
	        		$this->options = get_option( 'redeculturaviva_theme_options', array() );
				
	        	if( !array_key_exists('criar_estatos_cidades', $this->options) || $this->options['criar_estatos_cidades'] != 'S')
	        	{
	        		ini_set("memory_limit", "2048M");
	        		set_time_limit(0);
	        		global $EstadosCidades;
        			$EstadosCidades->create_location_terms();
	        	}
			}
        }*/
        
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        _e('Importações especiais do Tema:', 'redeculturaviva');
    }

    public static function log($msn, $print_r = false)
    {
    	if($print_r)
    	{
    		print_r($msn);
    		file_put_contents(dirname(__FILE__)."/csv_import.log", print_r($msn, true), FILE_APPEND);
    	}
    	else
    	{
	    	echo $msn;
	    	$msn = str_replace("<br/>", "\n", $msn);
	    	$msn = str_replace("<br>", "\n", $msn);
	    	file_put_contents(dirname(__FILE__)."/csv_import.log", $msn, FILE_APPEND);
    	}
    }
    
    public static function newLog()
    {
    	file_put_contents(dirname(__FILE__)."/csv_import.log", date('Y-m-d').'\n');
    }
    
    public function ImportarCsv_callback()
    {
    	CSVApi::newLog();
    	
    	$filename = '/tmp/import.csv';
    	
    	/*echo '<div id="result">';var_dump($_REQUEST);var_dump($_FILES);echo "</div>";
    	die();*/
    	
    	if(array_key_exists('media-importcsv-filename', $_FILES))
    	{
    		$filename = $_FILES['media-importcsv-filename']['tmp_name'];
    	}
    	
    	echo '<div id="result">';
		
		// include_once dirname(__FILE__).'/Tratar.php'; // TODO need?
		
		$debug = false;
		$begin = 0;
		$limit = 2;
		$ids = array ();
		
		ini_set ( "memory_limit", "2048M" );
		set_time_limit ( 0 );
		
		global $wpdb;
		
		$names = array ();
		
		if( file_exists($filename) )
		{
			
			$wpdb->query(
						"
							DELETE FROM {$wpdb->base_prefix}{$this->_table_name}
						"
			);
			
			$file = fopen ($filename , 'r' );
			
			// cabeçalho da planilha
			for($i = 0; $i < 1; $i ++) // first line has header
			{
				$row = fgetcsv ( $file, 0, ';' );
				$names [$i] = array_map ( 'trim', $row );
				
				$index = 0;
				$data = array('id' => 1);
				foreach ($row as $value)
				{
					$data[$this->_cols[$index]] = $value;
						
					$index++;
					if($index >= count($this->_cols)) break;
				}
				
				//$wpdb->delete( $wpdb->base_prefix . $this->_table_name, array( 'id' => 1 ) );
				$wpdb->insert( $wpdb->base_prefix . $this->_table_name, $data);
				
			}
			
			for($i = 0; $i < $begin; $i ++) // move pointer to begin of data
			{
				$row = fgetcsv ( $file, 0, ';' );
			}
			
			CSVApi::log ( '<pre>' );
			
			$row = fgetcsv ( $file, 0, ';' );
			$i = 0;
			do
			{
				if (count ( $ids ) > 0) // have ids limit
				{
					while ( $row !== false && ! in_array ( $row [3], $ids ) ) // locate next valid id
					{
						$row = fgetcsv ( $file, 0, ';' );
					}
					if ($row === false)
						break;
				}
				
				$index = 0;
				$data = array();
				foreach ($row as $value)
				{
					$data[$this->_cols[$index]] = $value;
					
					$index++;
					if($index >= count($this->_cols)) break;
				}
				
				$wpdb->insert($wpdb->base_prefix . $this->_table_name, $data);
				
				$row = fgetcsv( $file, 0, ';');
				$i++;
				
			} while ( $row !== false && (! $debug || $i < $limit) );
			CSVApi::log ( '</pre>' );
			fclose ( $file );
		}
		echo '</div>';
		die ();
	}
	
	function mask($val, $mask)
	{
		$maskared = '';
		$k = 0;
		for ($i = 0; $i <= strlen($mask) - 1; $i++)
		{
			if ($mask[$i] == '#')
			{
				if (isset ($val[$k]))
					$maskared .= $val[$k++];
			}
			else
			{
				if (isset ($mask[$i]))
					$maskared .= $mask[$i];
			}
		}
		return $maskared;
	}
	
	public function ajaxget_cultura()
	{
		if(array_key_exists('cnpj', $_REQUEST))
		{
			global $wpdb;
			
			$cnpj = sanitize_text_field($_REQUEST['cnpj']);
			
			$cnpj = preg_replace("/[^0-9]/","",$cnpj);
			
			$cnpj = $this->mask($cnpj, '##.###.###/####-##');
			
			if(strlen($cnpj) == 18)
			{
				$row = $wpdb->get_row( "SELECT * FROM {$wpdb->base_prefix}{$this->_table_name} WHERE w = \"{$cnpj}\"" );
				$ret = new stdClass();
				if(!is_null($row))
				{
					$names = $wpdb->get_row( "SELECT * FROM {$wpdb->base_prefix}{$this->_table_name} WHERE id = 1 " );
					
					foreach ($names as $key => $name)
					{
						if($key == 'id' || strlen(trim($name)) < 1) continue;
						$ret->$name = $row->$key;
					}
				}
				wp_send_json($ret);
			}
		}
		die('Formato inválido');
	}
	
}

global $CSVApi;
$CSVApi = new CSVApi ();