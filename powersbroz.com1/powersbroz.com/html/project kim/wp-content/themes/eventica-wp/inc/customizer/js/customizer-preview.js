
( function( $ ) {

	$.each( custStyle, function( index ) {

		var colorType 			= custStyle[index].type;
		var colorSlug			= custStyle[index].slug;

		var colorSelector 		= custStyle[index].selector;
		var colorProperty		= custStyle[index].property;
		var colorPropertyAdd	= custStyle[index].propertyadd;

		var colorSelector2 		= custStyle[index].selector2;
		var colorProperty2		= custStyle[index].property2;
		var colorPropertyAdd2	= custStyle[index].propertyadd2;

		if ( colorType == 'color' ) {
			wp.customize( colorSlug, function( value ) {
				value.bind( function( to ) {
					if ( colorSelector && colorProperty ) {
						$( colorSelector ).css( colorProperty, to ? to : '' );
					}
					if ( colorSelector2 && colorProperty2 ) {
						$( colorSelector2 ).css( colorProperty2, to ? to : '' );
					}
				});
			});

		} 
		else if ( colorType == 'text' || colorType == 'textarea' ) {
			wp.customize( colorSlug, function( value ) {
				value.bind( function( to ) {
					$( colorSelector ).html( to );
				} );
			} );

		} 
		else if ( colorType == 'images' ) {
			wp.customize( colorSlug, function( value ) {
				value.bind( function( to ) {
					$( colorSelector ).css( colorProperty, 'url(' + "'" + to + "'" + ')' + ' ' + colorPropertyAdd );
					$( colorSelector ).html( to );
				} );
			} );
		} 
		else if ( colorType == 'select_font' ) {
			wp.customize( colorSlug, function( value ) {
				value.bind( function( to ) {
					$( colorSelector ).css( colorProperty, to ? to : '' );
				});
			});

		} 

	});
	 
} )( jQuery );