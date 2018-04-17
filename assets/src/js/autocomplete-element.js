(function( $, scriptData ) {
	'use strict';

	function getDataProperty( propertyPath, data ) {
		var currentProperty;

		if ( ! propertyPath.length ) {
			return data;
		}

		if ( 'object' !== typeof data ) {
			return undefined;
		}

		currentProperty = propertyPath.shift();

		if ( ! data[ currentProperty ] ) {
			return undefined;
		}

		return getDataProperty( propertyPath, data[ currentProperty ] );
	}

	function replacePlaceholdersWithData( placeholderString, data ) {
		var replaced = placeholderString.replace( /%([A-Za-z0-9_.]+)%/g, function( match, placeholder ) {
			var propertyPath = placeholder.split( '.' );
			var value        = getDataProperty( propertyPath, data );

			if ( 'object' === typeof value ) {
				return match;
			}

			value = '' + value;

			if ( value && ! value.length ) {
				return match;
			}

			return value;
		});

		if ( -1 < replaced.search( '%' ) ) {
			return '';
		}

		return replaced;
	}

	$( '.torro-plugin-boilerplate-autocomplete' ).each( function() {
		var $control = $( this );
		var $hidden  = $( '#' + $control.data( 'targetId' ) );

		var searchRoute    = $control.data( 'searchRoute' );
		var valueGenerator = $control.data( 'valueGenerator' );
		var labelGenerator = $control.data( 'labelGenerator' );

		if ( ! $hidden.length ) {
			return;
		}

		$control.autocomplete({
			source: function( request, response ) {
				var restUrl = scriptData.restUrl + searchRoute.replace( '%search%', request.term );

				$.ajax( restUrl, {
					method:   'GET',
					dataType: 'json',
					headers:  {
						'X-WP-Nonce': scriptData.restNonce
					},

					success: function( data ) {
						var results = [];
						var value, label, i;

						for ( i in data ) {
							value = replacePlaceholdersWithData( valueGenerator, data[ i ] );
							label = replacePlaceholdersWithData( labelGenerator, data[ i ] );

							if ( value.length && label.length ) {
								results.push({
									value: value,
									label: label
								});
							}
						}

						response( results );
					},

					error: function( xhr ) {
						if ( 'object' === typeof xhr.responseJSON && null !== xhr.responseJSON && xhr.responseJSON.message ) {
							console.error( xhr.responseJSON.message );
						}

						response([]);
					},

					complete: function( xhr ) {
						var newNonce = xhr.getResponseHeader( 'X-WP-Nonce' );
						if ( newNonce ) {
							scriptData.restNonce = newNonce;
						}
					}
				});
			},
			select: function( e, ui ) {
				e.preventDefault();

				$control.val( ui.item.label );
				$hidden.val( ui.item.value ).trigger( 'change' );
			},
			focus: function( e, ui ) {
				e.preventDefault();

				$control.val( ui.item.label );
			}
		});
	});

})( window.jQuery, window.torroPluginBoilerplateAutocompleteData );
