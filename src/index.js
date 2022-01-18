/**
 * WordPress dependencies
 */

import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import StandaloneEditor from './editor';
import './editor/style.scss';

domReady(function () {
if (document.getElementById('standalone-gutenberg')) {
	render(
		<StandaloneEditor />,
		document.getElementById('standalone-gutenberg')
	);
}
});
 