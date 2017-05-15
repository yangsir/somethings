// native
(function(window, undefined) {
	window.App = {};
	/**
	 * variables
	 */
	var ua = navigator.userAgent.toUpperCase(), callindex = 0;

	// Android
	App.IS_ANDROID = ua.indexOf('ANDROID') != -1;
	// IOS
	App.IS_IOS = ua.indexOf('IPHONE OS') != -1;

	/**
	 * Callback native method
	 * @param {String} name (Method Name)
	 */

	App.call = function(name) {
		// Native parameters
		var args = Array.prototype.slice.call(arguments, 1);
		var callback = '', item = null;
		// parameters
		for ( var i = 0, len = args.length; i < len; i++) {
			item = args[i];
			if (item === "undefined") {
				item = '';
			}

			// Parameter is Function,Function save as window object, Native get function name
			if (typeof (item) == 'function') {
				callback = name + 'Callback' + i;
				window[callback] = item;
				item = callback;
			}
			args[i] = item;
		}
        
        // Android
		if (App.IS_ANDROID) {
			if (name == "setTitle") {
				return;
			}
			try {
				for ( var i = 0, len = args.length; i < len; i++) {
					// args[i] = '"' + args[i] + '"';
					args[i] = '\'' + args[i] + '\'';
				}
				eval('window.android.' + name + '(' + args.join(',') + ')');
			} catch (e) {
				console.log(e)
			}

		} else if (App.IS_IOS) {// IOS
			if (args.length) {
				args = '|' + args.join('|');
			}

			// IOS location.href callback native method
			callindex++;

		    //location.href = '#ios:' + name + args + '|' + callindex;
			var iframe = document.createElement("iframe");
			iframe.src = '#ios:' + name + args + '|' + callindex;
			iframe.style.display = "none";
			document.body.appendChild(iframe);
			iframe.parentNode.removeChild(iframe);
			iframe= null;
		}
	}
}(window));