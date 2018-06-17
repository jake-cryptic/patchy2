<div id="main">
	
	<div id="navigation">
		<?php @include("navigation.php"); ?>
	</div>
	
	<div id="content">
		
		<div id="header">
			<h1>Help & Support</h1>
		</div>
		
		<div id="helpSection">
			<fieldset>
				<legend><h2>FAQ</h2></legend>
				<div id="topics">
					<h3>Upload Help/Errors</h3>
					<h3>Account Help/Errors</h3>
					<h3>Patch Management Help/Errors</h3>
				</div>
				<div id="answers">
				
				</div>
			</fieldset>
		</div>
		<div id="contactSection">
			<fieldset>
				<legend><h2>Contact</h2></legend>
				<strong>When to contact support:</strong>
				<ol class="whenToContactUs">
					<li>If you receive an error telling you to contact us</li>
					<li>If you discover a bug, typo or other issue with the site</li>
					<li>If you are having issues with the service</li>
					<li>If you would like a feature</li>
					<li>If you have any questions about the service</li>
				</ol>
				Enquiries are sorted by importance; 1(important) - 5(less important) on the list<br /><br />
				<form action="assets/php/scripts/contact.php" method="post" name="contactForm">
					<textarea placeholder="Message to our support team" name="messageSupport" id="messageSupport" class="txtArea"></textarea>
					<input type="submit" value="Message Support" class="button-alt" />
				</form>
				<i><span style="color:white">Messages without an email/phone number attached will be ignored</span></i>
			</fieldset>
		</div>
		
	</div>
	
</div>