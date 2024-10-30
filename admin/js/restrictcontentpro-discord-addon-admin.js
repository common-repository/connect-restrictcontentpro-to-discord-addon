(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 $( document ).ready(function() {
		if (etsRestrictcontentParams.is_admin) {
			if(window.location.href.indexOf("res_") == -1 && jQuery("#skeletabsTab1").data('identity') == 'res_settings' ) {
				jQuery("#skeletabsTab1").trigger("click");
			}
			if(jQuery().select2) {
				$('#ets_restrictcontentpro_discord_redirect_url').select2({ width: 'resolve' });                
				$('#ets_restrictcontentpro_discord_redirect_url').on('change', function(){
					$.ajax({
						url: etsRestrictcontentParams.admin_ajax,
						type: "POST",
						context: this,
						data: { 'action': 'restrictcontentpro_discord_update_redirect_url', 'ets_restrictcontentpro_page_id': $(this).val() , 'ets_restrictcontentpro_discord_nonce': etsRestrictcontentParams.ets_restrictcontentpro_discord_nonce },
						beforeSend: function () {
							$('p.redirect-url').find('b').html("");
							$('p.ets-discord-update-message').css('display','none');                                               
							$(this).siblings('p.description').find('span.spinner').addClass("ets-is-active").show();
						},
						success: function (data) {

							$('p.redirect-url').find('b').html(data.formated_discord_redirect_url);
							$('p.ets-discord-update-message').css('display','block');                                               
						},
						error: function (response, textStatus, errorThrown ) {
							console.log( textStatus + " :  " + response.status + " : " + errorThrown );
						},
						complete: function () {
							$(this).siblings('p.description').find('span.spinner').removeClass("ets-is-active").hide();
						}
					});
	
				});                        
			}			
			/*Load all roles from discord server*/
			$.ajax({
				type: "POST",
				dataType: "JSON",
				url: etsRestrictcontentParams.admin_ajax,
				data: { 'action': 'restrictcontentpro_load_discord_roles', 'ets_restrictcontentpro_discord_nonce': etsRestrictcontentParams.ets_restrictcontentpro_discord_nonce, },
				beforeSend: function () {
					$(".res-discord-roles .spinner").addClass("is-active");
					$(".initialtab.spinner").addClass("is-active");
				},
				success: function (response) {
					if (response != null && response.hasOwnProperty('code') && response.code == 50001 && response.message == 'Missing Access') {
						$(".btn-connect-to-bot").show();
					} else if ( response.code === 10004 && response.message == 'Unknown Guild' ) {
						$(".btn-connect-to-bot").show().after('<p><b>The server ID is wrong or you did not connect the Bot.</b></p>');
					}else if( response.code === 0 && response.message == '401: Unauthorized' ) {
						$(".btn-connect-to-bot").show().html("Error: Unauthorized - The Bot Token is wrong").addClass('error-bk');																
					} else if (response == null || response.message == '401: Unauthorized' || response.hasOwnProperty('code') || response == 0) {
						$("#res-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
					} else {
						if ($('.ets-tabs button[data-identity="level-mapping"]').length) {
							$('.ets-tabs button[data-identity="level-mapping"]').show();
						}
						$("#res-connect-discord-bot").show().html("Bot Connected <i class='fab fa-discord'></i>").addClass('not-active');

						var activeTab = localStorage.getItem('activeTab');
						if ($('.ets-tabs button[data-identity="level-mapping"]').length == 0 && activeTab == 'level-mapping') {
							$('.ets-tabs button[data-identity="res_settings"]').trigger('click');
						}
						$.each(response, function (key, val) {
							var isbot = false;
							if (val.hasOwnProperty('tags')) {
								if (val.tags.hasOwnProperty('bot_id')) {
									isbot = true;
								}
							}

							if (key != 'previous_mapping' && isbot == false && val.name != '@everyone') {
								$('.res-discord-roles').append('<div class="makeMeDraggable" style="background-color:#'+val.color.toString(16)+'" data-role_id="' + val.id + '" >' + val.name + '</div>');
								$('#resdefaultRole').append('<option value="' + val.id + '" >' + val.name + '</option>');
								makeDrag($('.makeMeDraggable'));
							}
						});
						var defaultRole = $('#selected_default_role').val();
						if (defaultRole) {
							$('#resdefaultRole option[value=' + defaultRole + ']').prop('selected', true);
						}

						if (response.previous_mapping) {
							var resmapjson = response.previous_mapping;
						} else {
							var resmapjson = localStorage.getItem('RestrictcontentproMappingjson');
						}

						$("#res_maaping_json_val").html(resmapjson);
						$.each(JSON.parse(resmapjson), function (key, val) {
							var arrayofkey = key.split('id_');
							var preclone = $('*[data-role_id="' + val + '"]').clone();
							if(preclone.length>1){
								preclone.slice(1).hide();
							}
							$('*[data-level_id="' + arrayofkey[1] + '"]').append(preclone).attr('data-drop-role_id', val).find('span').css({ 'order': '2' });
							if (jQuery('*[data-level_id="' + arrayofkey[1] + '"]').find('.makeMeDraggable').length >= 1) {
								$('*[data-level_id="' + arrayofkey[1] + '"]').droppable("destroy");
							}
							preclone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'order': '1' }).attr('data-level_id', arrayofkey[1]);
							makeDrag(preclone);
						});
					}

				},
				error: function (response) {
					$("#res-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
					console.error(response);
				},
				complete: function () {
					$(".res-discord-roles .spinner").removeClass("is-active").css({ "float": "right" });
					$("#skeletabsTab1 .spinner").removeClass("is-active").css({ "float": "right", "display": "none" });
				}
			});


			/*Clear log log call-back*/
			$('#clrbtn').click(function (e) {
				e.preventDefault();
				$.ajax({
					url: etsRestrictcontentParams.admin_ajax,
					type: "POST",
					data: { 'action': 'restrictcontentpro_discord_clear_logs', 'ets_restrictcontentpro_discord_nonce': etsRestrictcontentParams.ets_restrictcontentpro_discord_nonce, },
					beforeSend: function () {
						$(".clr-log.spinner").addClass("is-active").show();
					},
					success: function (data) {
						if (data.error) {
							// handle the error
							alert(data.error.msg);
						} else {
							$('.error-log').html("Clear logs Sucesssfully !");
						}
					},
					error: function (response) {
						console.error(response);
					},
					complete: function () {
						$(".clr-log.spinner").removeClass("is-active").hide();
					}
				});
			});

			/*Call-back to manage member connection with discord from restrictcontentpro members-list*/
			$('.ets-restrictcontentpro-run-api').on('click', function (e) {
				e.preventDefault();
				var userId = $(this).data('uid');
				$.ajax({
					type: "POST",
					dataType: "JSON",
					url: etsRestrictcontentParams.admin_ajax,
					data: { 'action': 'restrictcontentpro_discord_member_table_run_api', 'user_id': userId, 'ets_restrictcontentpro_discord_nonce': etsRestrictcontentParams.ets_restrictcontentpro_discord_nonce, },
					beforeSend: function () {
						$("." + userId + ".spinner").addClass("is-active").show();
					},
					success: function (response) {
						if (response.status == 1) {
							$("." + userId + ".ets-save-success").show();;
						}
					},
					error: function (response) {
						console.error(response);
					},
					complete: function () {
						$("." + userId + ".spinner").removeClass("is-active").hide();
					}
				});
			});

			/*Flush settings from local storage*/
			$("#RestrictcontentproRevertMapping").click( function () {
				localStorage.removeItem('RestrictcontentproMapArray');
				localStorage.removeItem('RestrictcontentproMappingjson');
			});

			/*Create droppable element*/
			function init() {
				if( $('.makeMeDroppable').length){
					$('.makeMeDroppable').droppable({
						drop: handleDropEvent,
						hoverClass: 'hoverActive',
					});
				}
				if( $('.res-discord-roles-col').length){
					$('.res-discord-roles-col').droppable({
						drop: handlePreviousDropEvent,
						hoverClass: 'hoverActive',
					});
				}
			}

			$(init);

			/*Create draggable element*/
			function makeDrag(el) {
				// Pass me an object, and I will make it draggable
				if(el.draggable ){
				el.draggable({
					revert: "invalid",
					helper: 'clone',
					start: function(e, ui) {
					ui.helper.css({"width":"45%"});
					}
				});
			}
			}

			/*Handel droppable event for saved mapping*/
			function handlePreviousDropEvent(event, ui) {
				var draggable = ui.draggable;
				if(draggable.data('level_id')){
					$(ui.draggable).remove().hide();
				}
				$(this).append(draggable);
				$('*[data-drop-role_id="' + draggable.data('role_id') + '"]').droppable({
					drop: handleDropEvent,
					hoverClass: 'hoverActive',
				});
				$('*[data-drop-role_id="' + draggable.data('role_id') + '"]').attr('data-drop-role_id', '');

				var oldItems = JSON.parse(localStorage.getItem('RestrictcontentproMapArray')) || [];
				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'level_id_' + draggable.data('level_id') || arrayofval[1] == draggable.data('role_id')) {
							delete oldItems[key];
						}
					}
				});
				var jsonStart = "{";
				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] != 'level_id_' + draggable.data('level_id') || arrayofval[1] != draggable.data('role_id')) {
							jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
						}
					}
				});
				localStorage.setItem('RestrictcontentproMapArray', JSON.stringify(oldItems));
				var lastChar = jsonStart.slice(-1);
				if (lastChar == ',') {
					jsonStart = jsonStart.slice(0, -1);
				}

				var RestrictcontentproMappingjson = jsonStart + '}';
				$("#res_maaping_json_val").html(RestrictcontentproMappingjson);
				localStorage.setItem('RestrictcontentproMappingjson', RestrictcontentproMappingjson);
				draggable.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '10px' });
			}

			/*Handel droppable area for current mapping*/
			function handleDropEvent(event, ui) {
				var draggable = ui.draggable;
				var newItem = [];

				var newClone = $(ui.helper).clone();
				if($(this).find(".makeMeDraggable").length >= 1){
					return false;
				}

				$('*[data-drop-role_id="' + newClone.data('role_id') + '"]').droppable({
					drop: handleDropEvent,
					hoverClass: 'hoverActive',
				});
				$('*[data-drop-role_id="' + newClone.data('role_id') + '"]').attr('data-drop-role_id', '');
				if ($(this).data('drop-role_id') != newClone.data('role_id')) {
					var oldItems = JSON.parse(localStorage.getItem('RestrictcontentproMapArray')) || [];
					$(this).attr('data-drop-role_id', newClone.data('role_id'));
					newClone.attr('data-level_id', $(this).data('level_id'));

					$.each(oldItems, function (key, val) {
						if (val) {
							var arrayofval = val.split(',');
							if (arrayofval[0] == 'level_id_' + $(this).data('level_id')) {
								delete oldItems[key];
							}
						}
					});

					var newkey = 'level_id_' + $(this).data('level_id');
					oldItems.push(newkey + ',' + newClone.data('role_id'));
					var jsonStart = "{";
					$.each(oldItems, function (key, val) {
						if (val) {
							var arrayofval = val.split(',');
							if (arrayofval[0] == 'level_id_' + $(this).data('level_id') || arrayofval[1] != newClone.data('role_id') && arrayofval[0] != 'level_id_' + $(this).data('level_id') || arrayofval[1] == newClone.data('role_id')) {
								jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
							}
						}
					});

					localStorage.setItem('RestrictcontentproMapArray', JSON.stringify(oldItems));
					var lastChar = jsonStart.slice(-1);
					if (lastChar == ',') {
						jsonStart = jsonStart.slice(0, -1);
					}

					var RestrictcontentproMappingjson = jsonStart + '}';
					localStorage.setItem('RestrictcontentproMappingjson', RestrictcontentproMappingjson);
					$("#res_maaping_json_val").html(RestrictcontentproMappingjson);
				}

				//$(this).append(ui.draggable);
				//$(this).find('span').css({ 'order': '2' });
				$(this).append(newClone);
				$(this).find('span').css({ 'order': '2' });
				if (jQuery(this).find('.makeMeDraggable').length >= 1) {
					$(this).droppable("destroy");
				}
				makeDrag($('.makeMeDraggable'));
				newClone.css({ 'width': '100%', 'left': '0', 'top': '0','position':'unset', 'margin-bottom': '0px', 'order': '1' });
				//draggable.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'order': '1' });
			}
		}
		if( $.isFunction($.fn.wpColorPicker)){
			$('#ets_restrictcontentpro_discord_connect_button_bg_color').wpColorPicker();
			$('#ets_restrictcontentpro_discord_disconnect_button_bg_color').wpColorPicker();
		}
	});
	/*Tab options*/
	if( $.skeletabs ){
		$.skeletabs.setDefaults({
			keyboard: false,
		});
	}
})( jQuery );
