<?php
$form = betterPing::helper('form');
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Better Ping Settings</h2>
	<form action="options-general.php?page=<?php echo $url; ?>" method="post">

		<h3>Ping on Published:</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th>
						<label for="bp_publishPost">Posts</label>
					</th>
					<td>
						<select name="bp_publishPost" id="bp_publishPost">
							<?php echo $form->option('false', 'False', betterPing::getOption('bp_publishPost')); ?>
							<?php echo $form->option('true', 'True', betterPing::getOption('bp_publishPost')); ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th>
						<label for="bp_publishPage">Pages</label>
					</th>
					<td>
						<select name="bp_publishPage" id="bp_publishPage">
							<?php echo $form->option('false', 'False', betterPing::getOption('bp_publishPage')); ?>
							<?php echo $form->option('true', 'True', betterPing::getOption('bp_publishPage')); ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="urls">
			<h3><label for="bp_urls">Line seperated URL's to ping:</label></h3>
			<textarea name="bp_urls" id="bp_urls" class="large-text code" rows="5"><?php
				echo betterPing::getOption('bp_urls');
			?></textarea>
		</div>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>