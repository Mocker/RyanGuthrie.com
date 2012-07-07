

	<div style="width: 500px; margin-left: auto; margin-right: auto; text-align: left;">

	<form id="searchForm" method=POST action="/getxp/tennis/search">
		<input type="hidden" name="searchType" id='searchType' value="<?php echo $stype; ?>">
		<input type=hidden name="sport_name" value="<?php echo $sname; ?>">
		<script type="text/javascript">
			function setSearch(stype){
				$('#searchType').attr('value',stype);
			}
		</script>
		<ul class="tabs" data-tabs="tabs">
			<li <?php if($stype=='players'){ echo 'class="active"';}?> >
				<a href='#search_players' onClick="setSearch('players');">Search Players</a></li>
			<li <?php if($stype=='centers'){ echo 'class="active"';}?> >
				<a href='#search_centers' onClick="setSearch('centers');">Search Centers</a></li>
			<li <?php if($stype=='networks'){ echo 'class="active"';}?> >
				<a href='#search_networks' onClick="setSearch('networks');">Search Networks</a></li>
		</ul>
		<div class="tab-content">
		<div class="tab-pane <?php if($stype=='players'){ echo 'active';}?>" id="search_players">
		<fieldset>
			<legend>Players</legend>
			Name: <input type=text name="playerName"><br>
			Or near zip: <input type=text name="playerZip">			
		</fieldset></div>
		<div class="tab-pane <?php if($stype=='centers'){ echo 'active';}?>" id="search_centers">
		<fieldset>
			<legend>Centers</legend>
			Name: <input type=text name="centerName"><br>
			Or near zip: <input type=text name="centerZip">	
			
		</fieldset></div>
		<div class="tab-pane <?php if($stype=='networks'){ echo 'active';}?>" id="search_networks">
		<fieldset>
			<legend>Networks</legend>
			Name: <input type=text name="networkName"><br>
			Or near zip: <input type=text name="networkZip">	
		</fieldset>
		</div>
		</div>
		<input type="submit" name="submit" value="Search">
			
		</form>

		<?php if(isset($sresults)){ ?>
			<!-- SEARCH DUMP 
			<?php print_r($sresults); ?>
			-->

			<table>
				<tr>
				<?php foreach($sfields as $sfield){
					print "<th>$sfield</th>";
				}?>
				</tr>
				<?php foreach($sresults as $sr){
					print "<tr>";
					if($stype=='players'){
						print "<td><a href='/getxp/tennis/profile/".
						$sr['Profile']['id']."'>"
						.$sr['Profile']['name']."</a></td><td>".
						$sr['Profile']['hometown']."</td><td>".
						$sr['Profile']['level']."</td><td>".
						$sr['Profile']['zip']."</td>";
					}
					else if($stype=='centers'){
						print "<td><a href='/getxp/tennis/centers/".
						$sr['Center']['id']."'>".
						$sr['Center']['name']."</a></td><td>".
						$sr['Center']['description']."</td><td>".
						$sr['Center']['city']."</td><td>".
						$sr['Center']['state']."</td>";
					}
					else if($stype=='networks'){
						print "<td><a href='/getxp/tennis/networks/".
						$sr['Network']['id']."'>".
						$sr['Network']['name']."</a></td><td>".
						$sr['Network']['zip']."</td>";
					}
					print "</tr>";
				}?>
		
			</table>
		<?php } ?>

	</div>
