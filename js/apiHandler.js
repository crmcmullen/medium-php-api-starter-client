/* 
 * This file serves as a generic API handler
 *
 */
function apiHandler(varAPIKey, varAPIHost, varCallType, varFunctionName, varFunctionParams, varCallback) {

	var varApiParams = {
		apiKey: varAPIKey,
		apiToken: '',
		apiFunctionName: varFunctionName,
		apiFunctionParams: varFunctionParams
	};

	$.ajax({
		type: varCallType,
		url: varAPIHost,
		data: varApiParams,
		success: function (res) {

			// console.log('[apiCaller] ' + res);
			var jsonData = jQuery.parseJSON(res);

			if (jsonData.success === false) {
				console.log("Error Response: " + jsonData.response + " - " + jsonData.responseDescription);
				location.replace('./#');
			} else {
				// redirect to call back with the response - decoded
				if ((typeof varCallback !== 'undefined') && (varCallback !== null)) {
					varCallback(jsonData);
				} 
			}
		}
	});

} 