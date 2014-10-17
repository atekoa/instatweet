<?php
$hashtag = $_GET["tag"];
$tuit = $_GET["t"];
$insta = $_GET["i"];
$onlyphoto = $_GET["op"];
?>
<!DOCTYPE html>
<html lang="es">
<head>  
  <meta charset="utf-8" />
  <title>Instagram & twitter hashtag</title>
  <link rel="stylesheet" href="css/style.css" /> 
  <script src="js/jquery-1.7.1.min.js"></script>
  <script src="js/jquery.isotope.min.js"></script>
  <script src="js/tweetMachine.js"></script>
</head>

<body>
<section id="content">
<div id="container" class="photos clearfix isotope" style="position: relative;"></div>

<script>
$(document).ready(function() {
	var $container = $('#container');
	$(function(){
		$container.isotope({
			filter: '*',
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false,
			},
		});
	});

  if('<?php echo ($onlyphoto == "1") ?>') {
    $('#container').tweetMachine('<?php echo $hashtag; ?>', ({filter: function(tweet) { return (tweet.entities.media!=null) && (tweet.retweeted_status == null) }}));
  } else if('<?php echo ($tuit == "1") ?>') {
    ({filter: function(tweet) { return (tweet.retweeted_status == null) }})
    $('#container').tweetMachine('<?php echo $hashtag; ?>');
  }

  if('<?php echo ($insta == "1") ?>') {
  	var options = {
  		hash: '<?php echo $hashtag; ?>',
  		show: '100',
  		clientId: 'INSTAGRAM_CLIENT_ID'
  	};
  
  	apiEndpoint = "https://api.instagram.com/v1",
  	settings = {
  		hash: null,
  		search: null,
  		accessToken: null,
  		clientId: String,
  		show: null,
  		onLoad: null,
  		onComplete: null,
  		maxId: null,
  		minId: null
  	};
  	options && $.extend(settings, options);
  
  	function createPhotoElement(photo) {
    	if(photo!=null) {
    	  var photo_content = " "
      	if(photo.caption!=null) {
      		if(photo.caption.text!=null) {
      		  if(typeof photo.caption.text != 'undefined') {
        		 	var photo_content = photo.caption.text + " - ";
            } 
      		}
      	}
    
    		if($('#'+photo.id).length == 0) {
    			texto = "-";
    			if(photo_content != null) texto = photo_content;
    			lines = 35 + texto.length / 45 * 15;
          
          if(photo.link!=null) {
            var $newEls = $('<div>').addClass('photo')
    					.addClass('isotope-item')
              .attr('style', 'position: absolute; left: 0px; top: 0px; width:306px; min-height: 55px;')
    					//.attr('style', 'position: absolute; left: 0px; top: 0px;width:' + eval(photo.images.low_resolution.width) + 'px;height:' + eval(photo.images.low_resolution.height + lines) +'px')
    					.attr('id', photo.id)
    					.append($('<h3>').addClass('fullname')
                .append($('<img>').attr('src', 'inc/instagram32.png').attr('style', 'height:15px;padding-left:5px;padding-right:5px;'))
                .append($('<a>').attr('target', '_blank').attr('href', photo.link).html(photo.user.full_name + "&nbsp;&hearts;&nbsp;" + photo.likes.count))
              )
    					// .append($('<p>').addClass('text').html(texto))
              .append($('<a>').attr('target', '_blank').addClass('image').attr('href', photo.images.standard_resolution.url).attr('rel', photo.images.standard_resolution.url)
                  .append($('<img>').addClass('instagram-image').attr('src', photo.images.low_resolution.url).attr('width', eval(photo.images.low_resolution.width)).attr('height', eval(photo.images.low_resolution.height))
      						  .attr('style', 'z-index:-999;width:' + eval(photo.images.low_resolution.width) + 'px;height:' + eval(photo.images.low_resolution.height) +'px')
                )
              );
          } else {
            var $newEls = $('<div>').addClass('photo')
    					.addClass('isotope-item')
              .attr('style', 'position: absolute; left: 0px; top: 0px; width:306px; min-height: 55px;')
    					//.attr('style', 'position: absolute; left: 0px; top: 0px;width:' + eval(photo.images.low_resolution.width) + 'px;height:' + eval(photo.images.low_resolution.height + lines) +'px')
    					.attr('id', photo.id)
    					.append($('<h3>').addClass('fullname')
                .append($('<img>').attr('src', 'inc/instagram32.png').attr('style', 'height:15px;padding-left:5px;padding-right:5px;'))
                .html(photo.user.full_name + "&nbsp;&hearts;&nbsp;" + photo.likes.count))
    					.append($('<p>').addClass('text').html(texto))
              .append($('<a>').attr('target', '_blank').addClass('image').attr('href', photo.images.standard_resolution.url).attr('rel', photo.images.standard_resolution.url)
                  .append($('<img>').addClass('instagram-image').attr('src', photo.images.low_resolution.url).attr('width', eval(photo.images.low_resolution.width)).attr('height', eval(photo.images.low_resolution.height))
      						  .attr('style', 'z-index:-999;width:' + eval(photo.images.low_resolution.width) + 'px;height:' + eval(photo.images.low_resolution.height) +'px')
                )
              );
          }
    			
    			$container.prepend( $newEls )
    				.isotope('reloadItems')
    				.isotope({ sortBy: 'original-order' })
    				// set sort back to symbol for inserting
    				.isotope('option', { sortBy: 'symbol' });
    			return false;
    		}
    		}
  	}
  
  	function composeRequestURL() {
  		var url = apiEndpoint,
  		params = {};
  
  		if(settings.hash != null) {
  			url += "/tags/" + settings.hash + "/media/recent";
  		} else if(settings.search != null) {
  			url += "/media/search";
  			params.lat = settings.search.lat;
  			params.lng = settings.search.lng;
  			settings.search.max_timestamp != null && (params.max_timestamp = settings.search.max_timestamp);
  			settings.search.min_timestamp != null && (params.min_timestamp = settings.search.min_timestamp);
  			settings.search.distance != null && (params.distance = settings.search.distance);
  		} else {
  			url += "/media/popular";
  		}
  
  		settings.accessToken != null && (params.access_token = settings.accessToken);
  		settings.clientId != null && (params.client_id = settings.clientId);
  
  		url += "?" + $.param(params);
  
  		return url;
  	}
  
  	settings.onLoad != null && typeof settings.onLoad == 'function' && settings.onLoad();
  
  	(function poolInstagram(){
  		// alert("peticion");
  		$.ajax({
  			type: "GET",
  			dataType: "jsonp",
  			cache: false,
  			url: composeRequestURL(),
  			success: function(res) {
  				settings.onComplete != null && typeof settings.onComplete == 'function' && settings.onComplete(res.data);
  				var limit = settings.show == null ? res.data.length : settings.show;
  				for(var i = 0; i < limit; i++) {
  					createPhotoElement(res.data[i]);
  				}
  			},
  			complete: setTimeout(poolInstagram,20000),
  			timeout: 10000
  		});
  	})();
  }
});
</script>
    </body>
</html>
