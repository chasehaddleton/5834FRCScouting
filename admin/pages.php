<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');

verifyPermission($_SESSION['level'], 1);

printHead("Control Panel");
printNav();
?>

	<section id="main">
		<div class="row">
			<div class="small-12 columns">
				<h2>Control Panel</h2>

				<p>This project currently contains 2900 lines of code!
					<small>as of October 19th</small>
				</p>

				<h3>Controls</h3>
			</div>
		</div>
		<div class="row">
			<div class="small-12 medium-4 columns">
				<ul class="side-nav">
					<li><a href="/admin/editmaillist.php">Mailing List</a></li>
					<li><a href="/admin/mailusers.php">Email Students</a></li>
					<li class="divider"></li>
					<li><a href="/admin/editsponsors.php">Sponsors</a></li>
					<li class="divider"></li>
					<li><a href="/admin/editgallery.php">Gallery</a></li>
					<?php
					if ($_SESSION['level'] > 1) {
						echo "<li class='divider'></li>";
						echo "<li><a href='editusers.php'>Lead Members</a></li>";
						echo "<li><a href='viewlogs.php'>View Logs</a></li>";
					}
					?>
				</ul>
			</div>
			<div class="small-12 medium-8 column">
				<script>
					(function(w,d,s,g,js,fs){
						g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
						js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
						js.src='https://apis.google.com/js/platform.js';
						fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
					}(window,document,'script'));
				</script>

				<div id="embed-api-auth-container"></div>
				<div id="chart-container"></div>
				<div id="view-selector-container"></div>

				<script>
					gapi.analytics.ready(function() {

						/**
						 * Authorize the user immediately if the user has already granted access.
						 * If no access has been created, render an authorize button inside the
						 * element with the ID "embed-api-auth-container".
						 */
						gapi.analytics.auth.authorize({
							container: 'embed-api-auth-container',
							clientid: '448985272745-jkpmv87k65ifo6dakucqv29bh26chr7s.apps.googleusercontent.com'
						});


						/**
						 * Create a new ViewSelector instance to be rendered inside of an
						 * element with the id "view-selector-container".
						 */
						var viewSelector = new gapi.analytics.ViewSelector({
							container: 'view-selector-container'
						});

						// Render the view selector to the page.
						viewSelector.execute();


						/**
						 * Create a new DataChart instance with the given query parameters
						 * and Google chart options. It will be rendered inside an element
						 * with the id "chart-container".
						 */
						var dataChart = new gapi.analytics.googleCharts.DataChart({
							query: {
								metrics: 'ga:sessions',
								dimensions: 'ga:date',
								'start-date': '30daysAgo',
								'end-date': 'yesterday'
							},
							chart: {
								container: 'chart-container',
								type: 'LINE',
								options: {
									width: '100%'
								}
							}
						});


						/**
						 * Render the dataChart on the page whenever a new view is selected.
						 */
						viewSelector.on('change', function(ids) {
							dataChart.set({query: {ids: ids}}).execute();
						});

					});
				</script>
			</div>
		</div>
	</section>

<?php printFooter(); ?>