	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var name = $( "#name_city" ),
			allFields = $( [] ).add( name ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Длина названия " + n + " должна быть не меньше " +
					min + " и не больше " + max + "символов." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
/*
		function checkDoubleName(o, n){
			$.each($("#town option"), function(i,opt){
			    if ( opt.text === o.val ){
				    o.addClass( "ui-state-error" );
				    updateTips( n );
				    return false;
			    }
			});
			return true;
		}
*/

		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 180,
			width: 300,
			modal: true,
			buttons: {
				"Add the name": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					bValid = bValid && checkLength( name, "города", 2, 255 );
					bValid = bValid && checkRegexp( name, /^[-\s\wА-ЯЁа-яё]+$/i, "Поле не должно быть пустым и содержать не словесные символы." );
//					bValid = bValid && checkDoubleName(name, "Double name");
					if ( bValid ) {
						var v_country = $("#country option:selected").val();
						var v_region = $("#region option:selected").val();
						$.post ( '/engine/inc/portfolio/ajax.php', { act : "add_town", country : v_country, region : v_region, town : name.val() }, function(data){
//						    alert("Data Loaded: " + data);
						    getTowns_marked(v_region, 'town');
						});
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
  $('#town').change(function(){
		if($(this).val() == 'add_new_city') {
		    $( "#dialog-form" ).dialog( "open" );
		}
            }).change();

	});
