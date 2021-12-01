window.q_prefetchURIs = window.q_prefetchURIs || [];
document.querySelectorAll( 'a' ).forEach( function( el ) {
	el.addEventListener( 'mouseenter', function() {
		if ( ! window.q_prefetchURIs.includes( el.href ) ) {
			setTimeout( function() {
				if ( el.matches( ':hover' ) ) {
					const link = document.createElement( 'link' );
					link.rel = 'prefetch';
					link.as = 'document';
					link.href = el.href;

					document.head.appendChild( link );
				}
				window.q_prefetchURIs.push( el.href );
			}, 200 );
		}
	} )
} );