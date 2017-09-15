/*
 * SimpleModal Contact Form
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: contact.js 243 2010-03-15 14:23:14Z emartin24 $
 *
 */

jQuery(function ($) {
	var contact = {
		message: null,
		init: function () {
			$('#btn-keyword-list').click(function (e) {
				e.preventDefault();

				// load the contact form using ajax
				$.get("ajax/tools.php?task=add_new_keyword", function(data){
					var messages = data.split("|");
					if ( messages[0] == 1 ) {
						// create a modal dialog with the data
						var data2 = messages[1];
						$(data2).modal({
							closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
							position: ["15%",],
							overlayId: 'modalbox-overlay',
							containerId: 'modalbox-container',
							onOpen: contact.open,
							onShow: contact.show,
							onClose: contact.close
						});
					} else {
						$('#block_keywords_list').show();
						$('#block_keywords_list').html(messages[1]);
					}
				});
			});
			
			$('#btn_add_new_link').click(function (e) {
				e.preventDefault();

				// load the contact form using ajax
				$.get("ajax/tools.php?task=add_new_link", function(data){
					
					var messages = data.split("|");
					// create a modal dialog with the data
					if ( messages[0] == 1 ) {
						// create a modal dialog with the data
						var data2 = messages[1];
						$(data2).modal({
							closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
							position: ["15%",],
							overlayId: 'modalbox-overlay',
							containerId: 'modalbox-container',
							onOpen: contact.open,
							onShow: contact.show,
							onClose: contact.close
						});
					} else {
						$('#ajax-error-block').show();
						$('#ajax-error-block').html(messages[1]);
					}
				});
			});
			
			
			
			$('#btn_add_mainmenu_link').click(function (e) {
				e.preventDefault();

				// load the contact form using ajax
				$.get("ajax/tools.php?task=add_mainmenu_link", function(data){
					var messages = data.split("|");
					// create a modal dialog with the data
					if ( messages[0] == 1 ) {
						// create a modal dialog with the data
						var data2 = messages[1];
						// create a modal dialog with the data
						$(data2).modal({
							closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
							position: ["15%",],
							overlayId: 'modalbox-overlay',
							containerId: 'modalbox-container',
							onOpen: contact.open,
							onShow: contact.show,
							onClose: contact.close
						});
					} else {
						$('#ajax-error-block').show();
						$('#ajax-error-block').html(messages[1]);
					}
				});
			});
			
			$('a#edit-link').click(function (e) {
				e.preventDefault();

				var edit_link = e.target.getAttribute('href');
				$.get("ajax/tools.php?task=edit_link&"+edit_link, function(data){
					var messages = data.split("|");
					
					if ( messages[0] == 1 ) {
						// create a modal dialog with the data
						var data2 = messages[1];
						$(data2).modal({
								closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
								position: ["15%",],
								overlayId: 'modalbox-overlay',
								containerId: 'modalbox-container',
								onOpen: contact.open,
								onShow: contact.show,
								onClose: contact.close
							});
					} else {
						$('#ajax-error-block').show();
						$('#ajax-error-block').html(messages[1]);
					}
				});
			});
				
			$('a#edit-main-menu-link').click(function (e) {
				e.preventDefault();

				var edit_main_menu_link = e.target.getAttribute('href');
				$.get("ajax/tools.php?task=edit_mainmenu_link&"+edit_main_menu_link, function(data){
					var messages = data.split("|");
					// create a modal dialog with the data
					if ( messages[0] == 1 ) {
						// create a modal dialog with the data
						var data2 = messages[1];
						$(data2).modal({
								closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
								position: ["15%",],
								overlayId: 'modalbox-overlay',
								containerId: 'modalbox-container',
								onOpen: contact.open,
								onShow: contact.show,
								onClose: contact.close
							});
					} else {
						$('#ajax-error-block').show();
						$('#ajax-error-block').html(messages[1]);
					}
				});
			});
			
		},
		open: function (dialog) {
			// add padding to the buttons in firefox/mozilla
			if ($.browser.mozilla) {
				$('#modalbox-container .modalbox-button').css({
					'padding-bottom': '2px'
				});
			}
			// input field font size
			if ($.browser.safari) {
				$('#modalbox-container .modalbox-input').css({
					'font-size': '.9em'
				});
			}

			// dynamically determine height
			var h = 280;
			if ($('#modalbox-subject').length) {
				h += 26;
			}
			if ($('#modalbox-cc').length) {
				h += 22;
			}

			var title = $('#modalbox-container .modalbox-title').html();
			$('#modalbox-container .modalbox-title').html('Loading...');
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {
						$('#modalbox-container .modalbox-content').animate({
							height: h
						}, function () {
							$('#modalbox-container .modalbox-title').html(title);
							$('#modalbox-container form').fadeIn(200, function () {
								$('#modalbox-container #modalbox-name').focus();

								$('#modalbox-container .modalbox-cc').click(function () {
									var cc = $('#modalbox-container #modalbox-cc');
									cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
								});

								// fix png's for IE 6
								if ($.browser.msie && $.browser.version < 7) {
									$('#modalbox-container .modalbox-button').each(function () {
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
			$('#modalbox-container .modalbox-send').click(function (e) {
				e.preventDefault();
				// validate form
				if (contact.validate()) {
					var msg = $('#modalbox-container .modalbox-message');
					msg.fadeOut(function () {
						msg.removeClass('modalbox-error').empty();
					});
					//$('#modalbox-container .modalbox-title').html('In Progress... Please wait ...');
					$('#modalbox-container form').fadeOut(200);
					$('#modalbox-container .modalbox-content').animate({
						height: '80px'
					}, function () {
						var admin_url	= $('#modalbox-container #admin_url').val();
							if ( $('#modalbox-container #modal_type').val() == 'save_link' ) {
								var link_url	= $.URLEncode($('#link_url').val());
								var link_title	= $('#link_title').val();
								$.ajax({
									url: admin_url+'/ajax/tools.php',
									data: 'task=save_link&id='+$('#id').val()+'&link_title='+link_title+'&link_url='+link_url,
									type: 'post',
									cache: false,
									dataType: 'html',
									success: function (data) {
									
										window.location.href = admin_url;
									},
									error: contact.error
								});
							} else if ( $('#modalbox-container #modal_type').val() == 'save_mainmenu_link' ) {
								var link_url	= $('#link_url').val();
								var link_title	= $('#link_title').val();
								$.ajax({
									url: admin_url+'/ajax/tools.php',
									data: 'task=save_mainmenu_link&id='+$('#id').val()+'&link_title='+link_title+'&link_url='+link_url+'&link_css='+$('#link_class').val()+'&link_new_window='+$('#link_new_window').val()+'&link_order='+$('#link_order').val(),
									type: 'post',
									cache: false,
									dataType: 'html',
									success: function (data) {
									
										window.location.href = admin_url;
									},
									error: contact.error
								});
							} else if ( $('#modalbox-container #modal_type').val() == 'keyword_filter' ) {
							
								var chk_contribute = $('#chk_contribute').is(':checked');
								var is_checked		= 0;
								if ( chk_contribute ) {
									is_checked = 1;
								}
								
								var new_keywords	= $('#new_keyword_filter').val();

								ajaxGet(admin_url+'/ajax/tools.php?task=new_keyword&new_keywords='+new_keywords+'&chk_contribute='+is_checked, 'navigation-filter', false, 'block-removing-keyword-filter', admin_url);
								
							} else if ( $('#modalbox-container #modal_type').val() == 'new_link' ) {
								var link_url	= $.URLEncode($('#link_url').val());
								var link_title	= $('#link_title').val();
								$.ajax({
									url: admin_url+'/ajax/tools.php',
									data: 'task=new_link&link_title='+link_title+'&link_url='+link_url,
									type: 'post',
									cache: false,
									dataType: 'html',
									success: function (data) {
										window.location.href = admin_url;
									},
									error: contact.error
								});
								
							} else if ( $('#modalbox-container #modal_type').val() == 'new_mainmenu_link' ) {
								var link_url	= $('#link_url').val();
								var link_title	= $('#link_title').val();
								$.ajax({
									url: admin_url+'/ajax/tools.php',
									data: 'task=new_mainmenu_link&link_title='+link_title+'&link_url='+link_url+'&link_css='+$('#link_class').val()+'&link_new_window='+$('#link_new_window').val()+'&link_order='+$('#link_order').val(),
									type: 'post',
									cache: false,
									dataType: 'html',
									success: function (data) {
									
										window.location.href = admin_url;
									},
									error: contact.error
								});
								
							}
							
							$('#modalbox-container .modalbox-message').fadeOut();
							//$('#modalbox-container .modalbox-title').html('Goodbye...');
							$('#modalbox-container form').fadeOut(200);
							$('#modalbox-container .modalbox-content').animate({
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
					});
				}
				else {
					if ($('#modalbox-container .modalbox-message:visible').length > 0) {
						var msg = $('#modalbox-container .modalbox-message div');
						msg.fadeOut(200, function () {
							msg.empty();
							contact.showError();
							msg.fadeIn(200);
						});
					}
					else {
						$('#modalbox-container .modalbox-message').animate({
							height: '45px'
						}, contact.showError);
					}
					
				}
			});
		},
		close: function (dialog) {
			$('#modalbox-container .modalbox-message').fadeOut();
			$('#modalbox-container .modalbox-title').html('Goodbye...');
			$('#modalbox-container form').fadeOut(200);
			$('#modalbox-container .modalbox-content').animate({
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
			
			if ( $('#modalbox-container #modal_type').val() == 'save_link' ) {
				if (!$('#link_title').val()) {
					contact.message += 'Title is required. ';
				}
				
				if (!$('#link_url').val()) {
					contact.message += 'URL is required. ';
				}else {
					if (!contact.validateURL($('#link_url').val())) {
						contact.message += 'URL is invalid. ';
					}
				}
				
				
			} else if ( $('#modalbox-container #modal_type').val() == 'save_mainmenu_link' ) {
				if (!$('#link_title').val()) {
					contact.message += 'Title is required. ';
				}
				
				if (!$('#link_url').val()) {
					contact.message += 'URL is required. ';
				}else {
					
				}
				
				if (!$('#link_order').val()) {
					contact.message += 'Order is required. ';
				} else if ( isNaN($('#link_order').val()) ) {
					contact.message += 'Please specify Link Order with numeric value. ';
				}
			} else if ( $('#modalbox-container #modal_type').val() == 'keyword_filter' ) {
				if (!$('#new_keyword_filter').val()) {
					contact.message += 'Keyword(s) is required. ';
				}
			} else if ( $('#modalbox-container #modal_type').val() == 'new_link' ) { 
				if (!$('#link_title').val()) {
					contact.message += 'Link Title is required. ';
				}
				
				if (!$('#link_url').val()) {
					contact.message += 'Link URL is required. ';
				} else {
					if (!contact.validateURL($('#link_url').val())) {
						contact.message += 'URL is invalid. ';
					}
				}
			} else if ( $('#modalbox-container #modal_type').val() == 'new_mainmenu_link' ) { 
				if (!$('#link_title').val()) {
					contact.message += 'Link Title is required. ';
				}
				
				if (!$('#link_url').val()) {
					contact.message += 'Link URL is required. ';
				} else {
					if (!contact.validateURL($('#link_url').val())) {
						contact.message += 'URL is invalid. ';
					}
				}
				
				if ( !$('#link_order').val() ) {
					contact.message += 'Link Order is required. ';
				} else if ( isNaN($('#link_order').val()) ) {
					contact.message += 'Please specify Link Order with numeric value. ';
				}
			}

			if (contact.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
		},
		validateURL: function (url) {
			var RegexUrl = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			return RegexUrl.test(url);
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
			$('#modalbox-container .modalbox-message')
				.html($('<div class="modalbox-error"></div>').append(contact.message))
				.fadeIn(200);
		}
	};

	contact.init();

});