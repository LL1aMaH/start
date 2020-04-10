function post_query( url, name, data ) {

	let str = '';

	$.each( data.split('.'), function(k, v) {
		str += '&' + v + '=' + $('#' + v).val();
	} );

	$.ajax(

	{
		url : '/' + url,
		type: 'POST',
		data: name + '_f=1' + str,
		cache: false,
		success: function (result) {
			obj = jQuery.parseJSON(result);

			if (obj.message) alert ( obj.message );

			if (obj.go) go ( obj.go );
	}
	}
	);
}

function go (url) {
	window.location.href='/' + url;
}
