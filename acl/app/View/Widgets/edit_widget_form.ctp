<ul>
	<li>View this many articles:
		<input type="text" name="nr_of_art" class="nr_of_art" value="<?php echo $data["Widget"]["nr_of_articles_cond"]; ?>"></input>
	</li>
	<li style="text-align: right">
		<input type="button" class="save" value="Save" id="<?php echo rand();?>"></input>
		<input type="button" class="cancel" value="Cancel"></input></li>
</ul>