( function() {
	const searchEl = [
			'.wp-block-post-title',
			'.wp-block-query-loop',
			'.wp-block-post-content',
			'.entry-content',
			'h1',
			'h2',
		];
	let contentEl, 
		i;

	// Find the content element.
	for ( i = 0; i < searchEl.length; i++ ) {
		if ( ! contentEl ) {
			contentEl = document.querySelector( searchEl[ i ] );
		}
	}

	// Early exit if no content element was found.
	if ( ! contentEl ) {
		return;
	}

	// Get the ID of the content element.
	let contentElID = contentEl.id;
	if ( ! contentElID ) {
		contentElID = 'auto-skip-link-target';
		contentEl.id = contentElID;
	}

	// Get the parent element. This is where we'll inject the skip-link.
	let parentEl = document.querySelector( '.wp-site-blocks' );
	if ( ! parentEl ) {
		parentEl = document.body;
	}

	// Create the skip link.
	let skipLink = document.createElement( 'a' );
	skipLink.classList.add( 'skip-link' );
	skipLink.classList.add( 'screen-reader-text' );
	skipLink.href = '#' + contentElID;
	skipLink.innerHTML = window.skipToContent;

	// Inject the skip link.
	parentEl.insertAdjacentElement( 'afterbegin', skipLink );
}() );
