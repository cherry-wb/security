<html>
    <head>
	<title>Vulnerability Search</title>
	<link rel='stylesheet' type='text/css' href="_css/styles.css"/>
	<script type='text/javascript' src='_scripts/jquery.js'></script>
	<script type='text/javascript'>
	    $(document).ready(function() {
		$("#days").hide();
		$("#external").hide();
		$(".external-results").hide();
		$(".resultsType button").click(function(){
		    $('#internal').toggle();
		    $("#external").toggle();
		});
		$("#1337Day").load("parsers/1337dayParser.php .ExploitTable").prepend('<base href="http://1337day.com/">');
		$(".external-name").click(function() {
		    $(this).closest('.external-source').find('.external-results').slideToggle();
		});
		/*	$("#header").click(function() {
				var keyword = $('input[name="keyword"]').attr('value');
				var keyword = keyword.replace(/ /g,'+')
				alert(keyword);
				$("#container-page").load('searchForm.php?keyword=apache+tomcat&howMany=50&submit=Go&sort=DESC&match=loose&days=1&external=0 #content');
			})*/
	    });
	</script>
    </head>
    <body>
	<?php
	require '_functions/searchFormFunction.php';
	require '_scripts/htmldom/simple_html_dom.php';
	require 'parsers/ibmxforceParser.php';
	require 'parsers/osvdbParser.php';

	if (!isset($_GET['submit'])) {
	    showSearchBar('Enter search query', 50);
	} else {
	    $con = mysql_connect('localhost', 'root', '');
	    mysql_select_db('security_r2', $con);
	    $keyword = $_GET['keyword']; // keyword to search for
	    $sort = $_GET['sort']; // user chosen sorting method
	    $days = $_GET['days']; // how many days to look back
	    $match = $_GET['match']; // what kind of match
	    $external = $_GET['external']; //True or False (1 or 0)
	    $keywords = str_replace(' ', '+', $keyword);
	    $howMany = $_GET['howMany'];

	    showSearchBar($keyword, $howMany);
	    $numOfResults = numOfResults($keyword, $match);
	    ?>
    	<div id='container-page'>
    	    <div id='content'>
    		<div id='internal'>
    		    <div class='resultsType'>
    			<h2>Interal Results: <?php echo $numOfResults ?>, showing <?php echo $howMany ?></h2>
			    <?php if ($external) { ?>
				<button>Switch to External</button>
			    <?php } ?>
    		    </div>
			<?php
			search($keyword, $match, $howMany);
			mysql_close($con);
			?>
    		</div>
		    <?php if ($external) { ?>
			<div id='external'>
			    <div class='resultsType'>
				<h2>External Results - Click on source name to show results</h2>
				<button>Switch To Internal</button>
			    </div>
			    <div class='external-source'>
				<div class='external-name'>
				    <h2>IBM XForce</h2>
				</div>
				<a href='http://webapp.iss.net/Search.do?keyword=<?php echo $keywords ?>&searchType=vuln&x=9&y=6'>[Visit their site for more]</a>
				<div class='external-results'>
				    <?php ibmxforceParser($keyword); ?>
				</div>
			    </div>
			    <div class='external-source'>
				<div class='external-name'>
				    <h2>OSVDB</h2>
				</div>
				<a href='http://osvdb.org/search?search%5Bvuln_title%5D=<?php echo $keywords ?>&search%5Btext_type%5D=alltext'>[Visit their site for more]</a>
				<div class='external-results'>
				    <?php osvdbParser($keyword); ?>
				</div>
			    </div>
			    <div class="external-source">
				<div class="external-name">
				    <h2>1337Day</h2>
				</div>
				<div class="external-results">
				    <div id="1337Day">

				    </div>
				</div>
			    </div>
			</div>
		    </div>
		</div>
		<?php
	    }
	}
	?>
    </body>
</html>