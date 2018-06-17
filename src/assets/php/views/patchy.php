<div id="navigation">
	<?php @include("navigation.php"); ?>
</div>
	
<div id="content">
	<div id="future">
		<h2>Site version goals</h2>
		<table class="table">
			<thead>
				<tr>
					<th>Feature</th>
					<th>Next update</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody id="table_body">
				
			</tbody>
		</table>
	</div>
	<div id="patchy">
		<!--<h2><a href="http://patchy-a.co.nf/" title="Patchy Other">Patchy Global Server</a></h2>-->
		<script type="text/javascript">
		$('#table_body').html('<h2>Please wait...</h2>');
		$(document).ready(function(){
			$.ajaxSetup({
				error: function() {
					$('#table_body').html('<h2>Loading Fallback</h2>');
					$.ajaxSetup({
						error: function() {
							$('#table_body').html('<h2>Couldn\'t load from secure server or global server</h2>');
						}
					});
					
					var url = 'http://patchy-a.co.nf/update_list.php'
					$.get(url, function(response) {
						$('#table_body').html(response);
					});
				}
			});
			
			var url = 'https://googledrive.com/host/0B0RxtOYOzL0TRlVTNzI0cFN4OTA';
			$.get(url, function(response) {
				$('#table_body').html(response);
			});
		});
		</script>
	</div>
</div>