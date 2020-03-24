/* 
 * Cookie Handler
 */
function decodeCookie(varEncodedCookie) { 
	return decodeURIComponent(varEncodedCookie.replace(/\+/g, ' '));
}

function deleteCookie(varCookieName) { 
	setCookie(varCookieName, '', -1); 
}

function encodeCookie(varDecodedCookie) { 
	return encodeURIComponent(varDecodedCookie);
}

function getCookie(varCookieName) {
	var cookieValue = document.cookie.match('(^|;) ?' + varCookieName + '=([^;]*)(;|$)');
	return cookieValue ? cookieValue[2] : null;
}

function setCookie(varCookieName, varCookieValue, varExpiryDays) {
	if (varExpiryDays) {
		var date = new Date();
		date.setTime(date.getTime() + (varExpiryDays * 24 * 60 * 60 *1000));
		var expires = "; expires=" + date.toGMTString();
	} else {
		var expires = "";
	}
	document.cookie = varCookieName + "=" + varCookieValue + expires + "; path=/;";
}