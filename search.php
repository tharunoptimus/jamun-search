<?php
include("config.php");
include("classes/SiteResultsProvider.php");
include("classes/ImageResultsProvider.php");
include("libs/Mobile_Detect.php");

if(isset($_GET["term"])) {
	$term = $_GET["term"];
}
else{
	exit("You must enter a search term");
}
$detect = new Mobile_Detect();
$is_mobile=($detect->isMobile());

//Query Results Preprocessing ---<
    //Encode the entered query - Prevents Code Injection
        $term = htmlspecialchars($term);
    //Remove the empty spaces before the query $term
        if(substr($term,0,1)==" "){while(1){$term=substr($term,1);if(substr($term,0,1)!=" "){$term=$term;break;}if(substr($term,1)==" ")continue;}}
    //Remove the empty spaces after the query $term
        if(substr($term,-1)==" "){while(1){$term=substr($term,0,-1);if(substr($term,-1)!=" "){$term=$term;break;}if(substr($term,-1)==" ")continue;}}
//---->

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$inputTerm=$term; /*Null Results Provider - turned off*/ if($inputTerm==""){header("Location: http://jamunsearch.rf.gd");exit();}


//inOrganicResultsProvider ----->


/*For Showing Client's system time*/
$isShowTime=0;if(strstr($inputTerm,'time')||strstr($inputTerm,'Time')||strstr($inputTerm,'TIME')){$isShowTime=1;}

/*To Calculate*/
$calc = $term; $calcCheck = 0; $toShow = 0;
if(strstr($calc, 'calculate') || (strstr($calc, 'CALCULATE')) || (strstr($calc, 'Calculate')) || (strstr($calc, 'cALCULATE'))){
    $calcCheck = substr($calc, 10);
    if($calcCheck != ""){$toShow = 1;}
    $calcCheck = str_replace("+", "%2B", $calcCheck);
    $calcCheck = "1*%28" . $calcCheck . "%29" ;
    $term = "calculate";
}

/*User Told Hello*/
$userHello=0;if(strstr($inputTerm,'hello')||strstr($inputTerm,'Hello')||strstr($inputTerm,'hELLO')||strstr($inputTerm,'HELLO')||strstr($inputTerm,'Jamun')||strstr($inputTerm,'JAMUN')||strstr($inputTerm,'jamun')){$userHello=1;}

?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo "$term"; ?> - Jamun Search</title>
        <!--Search Engine developed by Jamun Search Team and was acquired by Privacy Defenders (NR) on 25/8/2019 for good. For Queries contact
    instagram.com/privacydefenders or privacydefenders@protonmail.com -->
    <link rel='shortcut icon' href='assets/images/favicon.ico' type='image/x-icon' />

	<meta name="description" content="Search the web for sites and images.">
	<meta name="keywords" content="Search engine, jamun, websites, image, image search">
	<meta name="author" content="Privacy Defenders - Jamun Search Team - tharunoptimus">

    <!--Need any Details about the Engine? Contact Developer: tharunoptimus@protonmail.com or github.com/tharunoptimus  -->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <?php 
        //Change Main Style Sheet 
		if($is_mobile){
			echo('<link rel="stylesheet" type="text/css" href="assets/css/mobilestyle.css">');
		}
		else{
			echo('<link rel="stylesheet" type="text/css" href="assets/css/style.css">');
		}
		
	?>
	
</head>
<body>
	<?php
		if ($is_mobile){ 
			echo('
			<div class="tallhead">
				<a href="http://jamunsearch.rf.gd/">
					<img src="assets/images/searchlogomain.png" title="Jamun Search"/>
				</a>
			</div>');
		}
	?>		
	
	<div class="wrapper">
		<div class="header">

			<div class="headerContent">
				<?php
					if ($is_mobile){}
					else{ 
						echo('
							<div class="logoContainer">
								<a href="http://jamunsearch.rf.gd">
								<img src="assets/images/searchlogo.png" title="Jamun Search"/>
								</a>
							</div>');
					}
				?>
				<div class="searchContainer">
					<form action="search.php" method="GET">
						<div class="searchBarContainer">
							<input type="hidden" name="type" value="<?php echo $type; ?>">
							<input class="searchBox" type="text" name="term" value="<?php echo $term; ?>" autocomplete="off">
							<button class="searchButton">
								<img src="assets/images/icons/search.png">
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="tabsContainer">
				<ul class="tabList">
					<li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
						<a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
							Sites
						</a>
					</li>
					<li class="<?php echo $type == 'images' ? 'active' : '' ?>">
						<a href='<?php echo "search.php?term=$term&type=images"; ?>'>
						<?php	if ($is_mobile){echo('Images (Beta)');}else{ echo('Images'); } ?>
						</a>
					</li>
				</ul>
			</div>

		</div>


		<div class="mainResultsSection">
			<?php
                if($type == "sites") {
                    $resultsProvider = new SiteResultsProvider($con);
                    $pageSize = 20;
                }
                else {
                    $resultsProvider = new ImageResultsProvider($con);
                    $pageSize = 30;
                }
                $numResults = $resultsProvider->getNumResults($term);
                if($numResults == '0'){
                    echo "<p class='resultsCount'>Why no results? <a href='noResultsWhy.html'>Learn More</a> </p>";
                }
                else{
                    echo "<p class='resultsCount'>$numResults results found</p>";
                }
                include("inOrganicResults.php");                
                echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
			?>
		</div>

		<div class="paginationContainer">
			<div class="pageButtons">
				<div class="pageNumberContainer">
					<img src="assets/images/pagination/pageStart.png">
				</div>
				<?php
                    $pagesToShow = 10;
                    $numPages = ceil($numResults / $pageSize);
                    $pagesLeft = min($pagesToShow, $numPages);
                    $currentPage = $page - floor($pagesToShow / 2);
                    if($currentPage < 1) {
                        $currentPage = 1;
                    }
                    if($currentPage + $pagesLeft > $numPages + 1) {
                        $currentPage = $numPages + 1 - $pagesLeft;
                    }
                    while($pagesLeft != 0 && $currentPage <= $numPages) {
                        if($currentPage == $page) {
                            echo "<div class='pageNumberContainer'>
                                    <img src='assets/images/pagination/pageSelected.png'>
                                    <span class='pageNumber'>$currentPage</span>
                                </div>";
                        }
                        else {
                            echo "<div class='pageNumberContainer'>
                                    <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                        <img src='assets/images/pagination/page.png'>
                                        <span class='pageNumber'>$currentPage</span>
                                    </a>
                            </div>";
                        }
                        $currentPage++;
                        $pagesLeft--;

                    }
				?>
				<div class="pageNumberContainer">
					<img src="assets/images/pagination/pageEnd.png">
				</div>
			</div>
		</div>

	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>