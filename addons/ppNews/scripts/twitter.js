$J(document).ready(function() {

	$J.ajax({
		url: 'http://www.horsezone.com.au/index.php?a=ap&addon=ampseNews&page=twitter_feed',
		dataType: 'json',
		success: function(data) {
			var content = "";
			var statusHTML = [];
			var damnEndTag = "\" >";
			$J.each(data, function(i,tweet){
				  var username = tweet.user.screen_name;
				  var url = "";
				  var status = tweet.text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(found_url) {
					  url = found_url;
					  return '';
					}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
					  return  reply.charAt(0) + '<a href="http://twitter.com/' + reply.substring(1) + damnEndTag + reply.substring(1) + '</a>';
					});
	             if( url != "" ) {
					 if( status.slice(-1) == " ") {
						status = status.substring( 0, status.length - 1 );
					 }
					status = '<a href="' + url + '">' + status + '</a>'; 
				 }
					statusHTML.push('<li><span>'+status+'</span> &nbsp; <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+tweet.id+damnEndTag+relative_time(tweet.created_at)+'</a></li>');
			}); // end each
		   $J('#twitter_update_list').html(statusHTML.join('') );
		   var $tweets = $J('#twitter_update_list li');
			if( $tweets.length > 0  ) {
				$J('#twitter_update_working').fadeOut();				
				$tweets.data('current', $tweets.length - 1);
				var repeat = function() {
					var $tweets = $J('#twitter_update_list li');
					var current = $tweets.data('current');
					if( current >= $tweets.length - 1 ) {
					   current = 0;
					}else {
					   current++;
					}
				   
					$tweets.fadeOut(600);
				   
					$tweets.eq(current).fadeIn(600);
					$tweets.data('current', current);
					setTimeout( repeat, 5500);
				}
				repeat();
			}

		} // end success
	});
 });


             

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}