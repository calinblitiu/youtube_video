if (navigator.platform == "Win32" && navigator.appName == "Microsoft Internet Explorer" && window.attachEvent)
{
	document.writeln('<style type="text/css">img { visibility:hidden; } </style>');
	window.attachEvent("onload", fnLoadPngs);
}


function fnLoadPngs()
{
	var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, '');
	var itsAllGood = (rslt != null && Number(rslt[1]) >= 5.5);

	for (var i = document.images.length - 1, img = null; (img = document.images[i]); i--)
	{
		if (itsAllGood && img.src.match(/\.png$/i) != null)
		{
			fnFixPng(img);
			img.attachEvent("onpropertychange", fnPropertyChanged);
		}
		img.style.visibility = "visible";
	}
}

function fnPropertyChanged()
{
	if (window.event.propertyName == "src")
	{
		var el = window.event.srcElement;
		if (!el.src.match(/transparent\.gif$/i))
		{
			el.filters.item(0).src = el.src;
			el.src = window.location.host + "images/transparent.gif";
		}
	}
}

function fnFixPng(img)
{
	var src = img.src;
	img.style.width = img.width + "px";
	img.style.height = img.height + "px";
	img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "', sizingMethod='scale')"
	img.src = window.location.host + "images/transparent.gif";
}