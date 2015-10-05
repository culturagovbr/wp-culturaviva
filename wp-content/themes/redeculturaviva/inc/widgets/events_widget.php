<?php

class Mapas_Culturais_Events_Widget extends WP_Widget
{
	
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
				'Mapas_Culturais_Events_Widget', // Base ID
				__( 'Mapas Culturais Events Widget', 'rede-cultura-viva' ), // Name
				array( 'description' => __( 'Mapas Culturais Events Widget', 'rede-cultura-viva' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance )
	{
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'Mapas_Culturais_Events_Widget', 'widget' );
		}
		
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}
		
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		
		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}
		
		ob_start();
		
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Mapas Culturais Events List', 'rede-cultura-viva' );
		
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		$events = $this->get_events();
		
		echo $args['before_widget'];
		if ( $title )
		{
			echo $args['before_title']. '<span '.( array_key_exists('cssclasstitle', $instance) && !empty($instance['cssclasstitle']) ? 'class="'.$instance['cssclasstitle'].'"' : ''). '>' . $title . '</span>'. $args['after_title'];
		}?>
		<ul>
		<?php
			if (count($events) > 0) :
				foreach ($events as $event) :  ?>
					<li>
						<div class="col1">
							<span class="post-date"><?php echo $event['date']; ?></span>
						</div>
						<div class="col2">
							<a href="<?php echo $event['url']; ?>"><?php echo $event['name']; ?></a>
						</div>
					</li>
				<?php endforeach;
			else : ?>
				<li>
					<?php _e('Nenhum evento cadastrado ainda', 'rede-cultura-viva'); ?>
				</li>
				<li>
					<?php _e('Você pode cadastrar um evento clicando aqui!', 'rede-cultura-viva'); ?>
				</li>
				<li>
					<a href="<?php echo '/eventos/create/'; ?>"><?php _e('Clicando aqui!', 'rede-cultura-viva'); ?></a>
				</li><?php
			endif; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		
		if ( ! $this->is_preview() )
		{
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'Mapas_Culturais_Events_Widget', $cache, 'widget' );
		}
		else
		{
			ob_end_flush();
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$cssclasstitle  = isset( $instance['cssclasstitle'] ) ? esc_attr( $instance['cssclasstitle'] ) : '';

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'cssclasstitle' ); ?>"><?php _e( 'Title CSS Class:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'cssclasstitle' ); ?>" name="<?php echo $this->get_field_name( 'cssclasstitle' ); ?>" type="text" value="<?php echo $cssclasstitle; ?>" /></p>
		<?php
		
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cssclasstitle'] = strip_tags($new_instance['cssclasstitle']);
		
		return $instance;
	}
	
	public function get_events()
	{
		$events = array();
		if(function_exists('pg_connect'))
		{
			$config = new Mapas_Culturais_Events_Widget_Config();
			
			$pgConnection = @pg_connect("host={$config->_pghost} port={$config->_pgport} dbname={$config->_pgdb} user={$config->_pguser} password={$config->_pgpasswd}");
			if($pgConnection !== false)
			{
				$sql = "
					SELECT
		                *
		            FROM
		                event e
		            JOIN 
		                event_occurrence eo 
		                    ON eo.event_id = e.id 
		                        AND eo.status > 0
		
		            WHERE
		                e.status > 0 AND
		                e.id IN (
		                    SELECT 
		                        event_id 
		                    FROM 
		                        recurring_event_occurrence_for('".date('Y-m-d')." 00:00:00', '".date('Y-m-d', strtotime('+1 year'))." 23:59:59', 'Etc/UTC', NULL) 
		                      
		                )
		            ORDER BY
		                eo.starts_on, eo.starts_at
					LIMIT 3
				";
				$result = @pg_query($pgConnection, $sql);
				
				if($result !== false)
				{
					// rule decode
					$week = array(
						'Sunday',
						'Monday',
						'Tuesday',
						'Wednesday',
						'Thursday',
						'Friday',
						'Saturday'
					);
					while ($row = pg_fetch_assoc($result))
					{
						$rule = json_decode($row['rule']);
						$idate = 0;
						switch ($rule->frequency)
						{
							case 'weekly':
								$days = get_object_vars($rule->day);
								$dayofweek = date('w');
								$min = strtotime('+2 years');
								
								if(array_key_exists(date('w', strtotime($rule->startsOn." 00:00:00")), $days)) // fix startsOn firt day of weekly event
								{
									$min = strtotime($rule->startsOn." ".$rule->startsAt);
								}
								
								foreach ($days as $key => $value)
								{
									$min = min(strtotime("next ".$week[$key], strtotime($rule->startsOn." 00:00:00")), $min);
								}
								$idate = $min;
							break;
							case 'daily':
								$today = strtotime(date('Y-m-d')." ".$rule->startsAt);
								if(time() > ($today + ($rule->duration * 60)) ) // next tomorow
								{
									$idate = strtotime('+1 day', $today);
								}
								else 
								{
									$idate = $today;
								}
							break;
							case 'once':
								$idate = strtotime($rule->startsOn);
							break;
						}
						
						$events[] = array('idate' => $idate, 'name' => $row['name'], 'date' => date('d/m', $idate), 'url' => "/evento/{$row['event_id']}/" );
						
					}
				}
			}
		}
		return $events;
	}
}

require dirname(__FILE__) . '/events_widget_config.php';