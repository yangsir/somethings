/*
 * SimpleModal Contact Form
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2007 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: contact.js 113 2008-03-15 15:36:21Z emartin24 $
 *
 */
function email_entry(i_form_id,i_entry_id){
	// load the contact form using ajax
		$.get("email_entry.php",{form_id: i_form_id, entry_id: i_entry_id}, function(data){
			// create a modal dialog with the data
			$(data).modal({
				close: false,
				overlayId: 'contact-overlay',
				containerId: 'contact-container',
				onOpen: contact.open,
				onShow: contact.show,
				onClose: contact.close
			});
		});
}

$(document).ready(function () {
	$('#ve_detail_table').columnHover(); 
	 
	
	// preload images
	var img = ['cancel.png','form_bottom.gif','form_top.gif','form_top_ie.gif','ajax-loader.gif','send.png'];
	$(img).each(function () {
		var i = new Image();
		i.src = 'images/simple_modal/' + this;
	});
});

function explode( delimiter, string ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: kenneth
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
 
    var emptyArray = { 0: '' };
 
    if ( arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined' )
    {
        return null;
    }
 
    if ( delimiter === ''
        || delimiter === false
        || delimiter === null )
    {
        return false;
    }
 
    if ( typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object' )
    {
        return emptyArray;
    }
 
    if ( delimiter === true ) {
        delimiter = '1';
    }
 
    return string.toString().split ( delimiter.toString() );
}

function trim( str, charlist ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: mdsjack (http://www.mdsjack.bo.it)
    // +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
    // +      input by: Erkekjetter
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: DxGx
    // +   improved by: Steven Levithan (http://blog.stevenlevithan.com)
    // *     example 1: trim('    Kevin van Zonneveld    ');
    // *     returns 1: 'Kevin van Zonneveld'
    // *     example 2: trim('Hello World', 'Hdle');
    // *     returns 2: 'o Wor'
 
    var whitespace;
    
    if(!charlist){
        whitespace = ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000';
    } else{
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    }
  
  for (var i = 0; i < str.length; i++) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
    str = str.substring(i);
    break;
    }
  }
  for (i = str.length - 1; i >= 0; i--) {
    if (whitespace.indexOf(str.charAt(i)) === -1) {
      str = str.substring(0, i + 1);
      break;
      }
  }
  return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

var contact = {
	message: null,
	open: function (dialog) {
		// add padding to the buttons in firefox/mozilla
		if ($.browser.mozilla) {
			$('#contact-container .contact-button').css({
				'padding-bottom': '2px'
			});
		}
		// input field font size
		if ($.browser.safari) {
			$('#contact-container .contact-input').css({
				'font-size': '.9em'
			});
		}

		var title = $('#contact-container .contact-title').html();
		$('#contact-container .contact-title').html('Loading...');
		dialog.overlay.fadeIn(200, function () {
			dialog.container.fadeIn(200, function () {
				dialog.data.fadeIn(200, function () {
					$('#contact-container .contact-content').animate({
						height: 205
					}, function () {
						$('#contact-container .contact-title').html(title);
						$('#contact-container form').fadeIn(200, function () {
							$('#contact-container #contact-name').focus();

							// fix png's for IE 6
							if ($.browser.msie && $.browser.version < 7) {
								$('#contact-container .contact-button').each(function () {
									if ($(this).css('backgroundImage').match(/^url[("']+(.*\.png)[)"']+$/i)) {
										var src = RegExp.$1;
										$(this).css({
											backgroundImage: 'none',
											filter: 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' +  src + '", sizingMethod="crop")'
										});
									}
								});
							}
						});
					});
				});
			});
		});
	},
	show: function (dialog) {
		$('#contact-container .contact-send').click(function (e) {
			e.preventDefault();
			// validate form
			if (contact.validate()) {
				$('#contact-container .contact-message').fadeOut(function () {
					$('#contact-container .contact-message').removeClass('contact-error').empty();
				});
				$('#contact-container .contact-title').html('Sending...');
				$('#contact-container form').fadeOut(200);
				$('#contact-container .contact-content').animate({
					height: '110px'
				}, function () {
					if ($.browser.msie){
						$('#contact-container .contact-loading').css("margin-left","-40px");
					}
					$('#contact-container .contact-loading').fadeIn(200, function () {
						$.ajax({
							url: 'email_entry.php',
							data: $('#contact-container form').serialize() + '&action=send',
							type: 'post',
							cache: false,
							dataType: 'html',
							complete: function (xhr) {
								$('#contact-container .contact-loading').fadeOut(200, function () {
									$('#contact-container .contact-title').html('Email sent.');
									$('#contact-container .contact-message').html(xhr.responseText).fadeIn(200);
								});
							},
							error: contact.error
						});
					});
				});
			}
			else {
				if ($('#contact-container .contact-message:visible').length > 0) {
					var msg = $('#contact-container .contact-message div');
					msg.fadeOut(200, function () {
						msg.empty();
						contact.showError();
						msg.fadeIn(200);
					});
				}
				else {
					$('#contact-container .contact-message').animate({
						height: '30px'
					}, contact.showError);
				}
				
			}
		});
	},
	close: function (dialog) {
		$('#contact-container .contact-message').fadeOut();
		$('#contact-container .contact-title').html('&nbsp;');
		$('#contact-container form').fadeOut(200);
		$('#contact-container .contact-content').animate({
			height: 40
		}, function () {
			dialog.data.fadeOut(200, function () {
				dialog.container.fadeOut(200, function () {
					dialog.overlay.fadeOut(200, function () {
						$.modal.close();
					});
				});
			});
		});
	},
	error: function (xhr) {
		alert(xhr.statusText);
	},
	validate: function () {
		contact.message = '';
		
		var email = $('#contact-container #contact-email').val();
		if (!email) {
			contact.message += 'Email is required. ';
		}
		else {
			email_array = explode(',',email);
			var arLen=email_array.length;
			for ( var i=0; i<arLen; ++i ){
			   	if (!contact.validateEmail(trim(email_array[i]))) {
					contact.message += 'This field is not in the correct email format. ';
					break;
				}
			}

		}

		
		if (contact.message.length > 0) {
			return false;
		}
		else {
			return true;
		}
	},
	validateEmail: function (email) {
		var at = email.lastIndexOf("@");

		// Make sure the at (@) sybmol exists and  
		// it is not the first or last character
		if (at < 1 || (at + 1) === email.length)
			return false;

		// Make sure there aren't multiple periods together
		if (/(\.{2,})/.test(email))
			return false;

		// Break up the local and domain portions
		var local = email.substring(0, at);
		var domain = email.substring(at + 1);

		// Check lengths
		if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
			return false;

		// Make sure local and domain don't start with or end with a period
		if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
			return false;

		// Check for quoted-string addresses
		// Since almost anything is allowed in a quoted-string address,
		// we're just going to let them go through
		if (!/^"(.+)"$/.test(local)) {
			// It's a dot-string address...check for valid characters
			if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
				return false;
		}

		// Make sure domain contains only valid characters and at least one period
		if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
			return false;	

		return true;
	},
	showError: function () {
		$('#contact-container .contact-message')
			.html($('<div class="contact-error">').append(contact.message))
			.fadeIn(200);
	}
};