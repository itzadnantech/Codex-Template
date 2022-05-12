<?php

/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
	if (did_action('elementor/loaded') && hello_header_footer_experiment_active()) {
		get_template_part('template-parts/dynamic-footer');
	} else {
		get_template_part('template-parts/footer');
	}
}
?>

<?php wp_footer(); ?>

<!-- year listing tab css -->
<style>
	.year-active {
		background-color: #0d8722 !important;
		color: white !important;
	}
</style>

<?php
///filter url
$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace("/", "", $uri);


if (str_contains($uri, 'news-release-detail')) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'https://megenergy.mediaroom.com/api/newsfeed_releases/get.php?format=json&id=' . $_GET['id']);
	$result = curl_exec($ch);
	curl_close($ch);
	$result = str_replace("\\", "\\\\", $result);
}
?>


<?php if (str_contains($uri, 'news-release-detail')) { ?>
	<script>
		var txt = `<?php echo $result; ?>`;
		if (window.location.href.indexOf("id") > -1) {
			var obj = JSON.parse(txt);

			if (obj.headline != null && obj.headline != "") {
				document.getElementById('headline').children[0].children[0].textContent = obj.headline;
			} else {
				document.getElementById('headline').style.display = "none";
			}
			if (obj.releaseDate != null && obj.releaseDate != "") {
				document.getElementById('releaseDate').children[0].children[0].textContent = obj.releaseDate;
			} else {
				document.getElementById('releaseDate').style.display = "none";
			}
			if (obj.subheadline != null && obj.subheadline != "") {
				document.getElementById('subheadline').children[0].children[0].textContent = obj.subheadline;
			} else {
				document.getElementById('subheadline').style.display = "none";
			}
			if (obj.image_url != null && obj.image_url != "") {
				document.getElementById('image_url').children[0].children[0].src = obj.image_url;
			} else {
				document.getElementById('image_url').style.display = "none";
			}

			if (obj.body != null && obj.body != "") {
				document.getElementById('body').children[0].children[0].innerHTML = obj.body;
			} else {
				document.getElementById('body').style.display = "none";
			}
			if (obj.location != null && obj.location != "") {
				document.getElementById('location').children[0].children[0].textContent = obj.location;
			} else {
				document.getElementById('location').children[0].children[0].style.display = "none";
			}
			if (obj.summary != null && obj.summary != "") {
				document.getElementById('summary').children[0].children[0].innerHTML = obj.summary;
			} else {
				document.getElementById('summary').style.display = "none";
			}




		}
	</script>
<?php } ?>


<!-- Global script -->
<!-- Global script -->
<!-- Global script -->
<!-- Global script -->
<!-- Global script -->

<script>
	function date_format(date) {
		let date_arr = date.split(" ");
		let new_date = date_arr[2] + " " + date_arr[1] + " " + date_arr[3];
		return new_date;

	}



	function checkUrl() {
		let url = window.location.href;
		if (window.location.href.indexOf("news-releases") > -1) {
			if (window.location.href.indexOf("date") < 1) {
				$('#2022').addClass('year-active');
			} else {
				let year = `<?php echo $_GET['date'] ?>`;
				$('#' + year).addClass('year-active');
			}
		}

	}

	$(function() {

		checkUrl();
	})
</script>


<!-- code news listing page -->
<!-- code news listing page -->
<!-- code news listing page -->
<!-- code news listing page -->
<!-- code news listing page -->
<!-- code news listing page -->
<!-- code news listing page -->


<?php
if (str_contains($uri, 'news-releases')) {

	function lastday($dateValue)
	{

		$time = strtotime($dateValue);
		$year = date("Y", $time);
		$currentY = date('Y');
		$lastyearE = mktime(0, 0, 0, 12, 31,  $year);
		return  date('Y-m-d', $lastyearE);
	}

	///check filter
	if (isset($_GET['date'])) {
		$start_date = $_GET['date'] . '-01-01';
		$end_date = date("Y-m-d", strtotime("Last day of December", strtotime($start_date)));
		$url = 'https://megenergy.mediaroom.com/api/newsfeed_releases/list.php?format=json&start_date=' . $start_date . '&end_date=' . $end_date;
		// echo $url;
		// die;
	} else {
		$start_date = date('Y') . '-01-01';
		$end_date = date("Y-m-d", strtotime("Last day of December", strtotime($start_date)));
		$url = 'https://megenergy.mediaroom.com/api/newsfeed_releases/list.php?format=json&start_date=' . $start_date . '&end_date=' . $end_date;
	}
	///get listing manually  
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',

	));

	$response = curl_exec($curl);
	// $response = json_decode($response);

	curl_close($curl);
}
?>

<!-- ///convert list into javascript -->
<?php if (str_contains($uri, 'news-releases')) { ?>
	<script>
		var list = `<?php echo $response; ?>`;
		list = JSON.parse(list);



		///list count
		count = list.returned_count;
		let headline = null;
		let releaseDate = null;
		let news_id = null;
		let container = "";
		let row = "";
		var href = $('.news-readmore .elementor-button').attr('href');
		//	console.log(href);
		$('#news-listing').css('display', 'none');
		$('#no_record').css('display', 'none');

		// document.getElementById('news-listing').innerHTML = "";






		///loop for each item
		if (count > 0) {
			for (let i = 0; i < count; i++) {

				news_id = list.release[i]['id'];
				headline = list.release[i]['headline'];
				releaseDate = date_format(list.release[i]['releaseDate']);

				$('#news-listing div:first .news-date div').children().text(releaseDate)
				$('#news-listing div:first .news-headline div').children().text(headline)
				//	$('#news-listing div:first .news-readmore div').children().text(news_id)
				$('.news-readmore .elementor-button').attr('href', href + news_id)
				row = $('#news-listing').html();
				container = container + row;
				console.log(row);


			}
			console.log('<!------------Container-------------->');
			console.log(container);
			$('#news-listing').css('display', 'block');
			document.getElementById('news-listing').innerHTML = container;
		} else {
			$('#no_record').css('display', 'block');
		}
		// console.log(row);
	</script>






<?php } ?>

<!-- code news listing end -->
<!-- code news listing end -->
<!-- code news listing end -->
<!-- code news listing end -->
<!-- code news listing end -->
<!-- code news listing end -->



<!------------------------ Code Stock Prices------------------->
<!------------------------ Code Stock Prices------------------->
<!------------------------ Code Stock Prices------------------->
<!------------------------ Code Stock Prices------------------->
<!------------------------ Code Stock Prices------------------->

<?php
$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MEG.TO&outputsize=compact&apikey=BQY36KAEXY4UF6NC',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
));

$stock_response = curl_exec($curl);
$stock_response_obj = json_decode($stock_response);
curl_close($curl);

$stock_response_arr = (array) $stock_response_obj;


if ($stock_response) {
	///convert meta data to array
	$meta_data = $stock_response_arr['Meta Data'];
	$meta_data = (array)$meta_data;
	$today = $meta_data['3. Last Refreshed'];
	$symbol = $meta_data['2. Symbol'];


	///convert time serieas into array
	$latest_stock_prices = $stock_response_arr['Time Series (Daily)'];
	$latest_stock_prices_today = $latest_stock_prices->$today;
	$latest_stock_prices_today = (array) $latest_stock_prices_today;

	///Requer Variable 
	$stock_price_open = $latest_stock_prices_today['1. open'];
	$stock_price_high = $latest_stock_prices_today['2. high'];
	$stock_price_low = $latest_stock_prices_today['3. low'];
	$stock_price_close = $latest_stock_prices_today['4. close'];
	$stock_price_volume = $latest_stock_prices_today['5. volume'];
}

?>
<script>
	var stock_price_close = `<?php echo $stock_price_close; ?>`;
	stock_price_close = parseFloat(stock_price_close).toFixed(2);
	console.log(stock_price_close);

	$('#stock-prices .elementor-heading-title').fadeIn("slow").text('TSX: MEG $' + stock_price_close);
</script>
















<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->
<!------------------------ New Listing for home page ------------------->

<?php
if (empty($uri)) {
	///get listing manually  
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://megenergy.mediaroom.com/api/newsfeed_releases/list.php?format=json',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',

	));

	$response = curl_exec($curl);
	// $response = json_decode($response);

	curl_close($curl);
}
?>

<!-- ///convert list into javascript -->
<?php if (empty($uri)) { ?>

	<script>
		var list = `<?php echo $response; ?>`;
		list = JSON.parse(list);

		///list count
		let count = 4;

		///elementors elements ids
		let column_id = '9821815';
		let press_release_id = '4dff875';
		let release_date_id = '1a0cf19';
		let headline_id = 'b2d461c';

		let url = `<?php echo $uri ?>`;



		let headline = null;
		let releaseDate = null;
		let news_id = null;
		let j = 1;
		let container = "";
		let row = "";
		let column = "";
		var href = $('.news-readmore .elementor-button').attr('href');
		let start_container = "<div class='elementor-container elementor-column-gap-default' style='margin-top:20px'>";
		let end_container = "</div>";
		$('#news-listing').css('display', 'none');
		$('#no_record').css('display', 'none');
		// document.getElementById('news-listing').innerHTML = "";

		///loop for each item 
		for (let i = 0; i < count; i++) {
			// console.log(list.release[i]['headline']);
			news_id = list.release[i]['id'];
			headline = list.release[i]['headline'];
			releaseDate = date_format(list.release[i]['releaseDate']);
			// releaseDate = list.release[i]['releaseDate'];



			// $('#news-listing div:first .news-date div').children().text(releaseDate)
			// $('#news-listing div:first .news-headline div').children().text(headline)
			// //	$('#news-listing div:first .news-readmore div').children().text(news_id)
			// $('.news-readmore .elementor-button').attr('href', href + news_id)
			// row = $('#news-listing').html();

			column = "";
			column = "<div class='elementor-column elementor-col-25 elementor-inner-column elementor-element elementor-element-" + column_id + " meg-news-card' data-id='" + column_id + "' data-element_type='column' data-settings='{&quot;background_background&quot;:&quot;gradient&quot;}'>" +
				"<div class='elementor-widget-wrap elementor-element-populated'>" +
				"<div class='elementor-background-overlay'>" + "</div>" +
				"<div class='elementor-element elementor-element-" + press_release_id + " elementor-widget__width-auto elementor-widget elementor-widget-heading' data-id='" + press_release_id + "' data-element_type='widget' data-widget_type='heading.default'>" +
				"<div class='elementor-widget-container'>" +
				"<h6 class='elementor-heading-title elementor-size-default'><a href='/news-release-detail?id=" + news_id + "'>Press Release</a></h6>" +
				"</div>" +
				"</div>" +
				"<div class='elementor-element elementor-element-" + release_date_id + " release_date elementor-widget elementor-widget-heading' data-id='" + release_date_id + "' data-element_type='widget' data-widget_type='heading.default'>" +
				"<div class='elementor-widget-container'>" +
				"<span class='elementor-heading-title elementor-size-default'>" + releaseDate + "</span>" +
				"</div>" +
				"</div>" +
				"<div class='elementor-element elementor-element-" + headline_id + " news_headline elementor-widget elementor-widget-heading' data-id='" + headline_id + "' data-element_type='widget' data-widget_type='heading.default'>" +
				"<div class='elementor-widget-container'>" +
				"<h5 class='elementor-heading-title elementor-size-default'>" + headline + "</h5>" +
				"</div>" +
				"</div>" +
				"</div>" +
				"</div>";

			if (j == 5) {
				container = container + start_container + row + end_container;
				row = "";
				j = 1;
			} else {
				row = row + column;
				if (i == count - 1) {
					container = container + start_container + row + end_container;
					row = "";
					continue;

				}
				j++;
			}



		}
		$('#news-listing').css('display', 'block');
		// document.getElementById('news-listing').innerHTML = container;
	</script>






<?php } ?>

<!------------------------ New Listing for home end ------------------->
<!------------------------ New Listing for home end ------------------->
<!------------------------ New Listing for home end ------------------->
<!------------------------ New Listing for home end ------------------->
<!------------------------ New Listing for home end ------------------->






















</body>

</html>