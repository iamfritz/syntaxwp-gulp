<div class="wrap">
	<h1>Expired Business</h1>

<div class="updated" style=""> <p>Manual Check of Subscription. Please Click <a href="<?php echo admin_url( 'edit.php?post_type=business&page=user-subscription&s_action=checksubscripton');?>">Here</a> to run updates.</p></div>
<table class="wp-list-table widefat fixed striped pages">
	<thead>
	<tr>
		<th>Business</th>
		<th>Owner</th>
		<th>Subscription Date</th>
		<th>Status</th>
	</thead>

	<tbody id="the-list">
		<?php
			foreach ($expired_list as $post) {
				$post_id    = $post->ID;
            	$date_start = get_field('date_start', $post_id );
            	$date_end   = get_field('date_end', $post_id );		
            	$biz_date   = ($date_start && $date_end) ? date("F j, Y", strtotime($date_start)).' to '.date("F j, Y", strtotime($date_end)) : '<i>Pending</i>';		
				echo '
				<tr>
					<td><a href="'.admin_url( 'post.php?post='.$post_id.'&action=edit').'" target="_blank">'.$post->post_title.'</a></td>
					<td>'.get_the_author_meta( 'first_name', $post->post_author).'</td>
					<td>'.$biz_date.'</td>
					<td>'.$post->post_status.'</td>
				</tr>
				';				
			}

		?>
	</tbody>
</table>

<div id="ajax-response"></div>
<br class="clear">
</div>