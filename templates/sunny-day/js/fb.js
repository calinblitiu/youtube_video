
	window.fbAsyncInit = function() {
	FB.init({appId: '126995607311247', status: true, cookie: true,
			 xfbml: true});
	};
	(function() {
	var e = document.createElement('script'); e.async = true;
	e.src = document.location.protocol +
	  '//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
	}());
