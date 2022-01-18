/**
 * WordPress dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import {
	BlockEditorKeyboardShortcuts,
	BlockEditorProvider,
	BlockList,
	BlockTools,
	BlockInspector,
	WritingFlow,
	ObserveTyping,
} from '@wordpress/block-editor';
import { Popover, SlotFillProvider } from '@wordpress/components';
import { registerCoreBlocks } from '@wordpress/block-library';
import '@wordpress/format-library';
import { parse } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Header from './components/header';
import Notices from './components/notices';
import './style.scss';

const { blocks:allBlocks = [] } = window.sg;

function StandaloneEditor() {
	const [blocks, updateBlocks] = useState([]);

	useEffect( () => {
		registerCoreBlocks();
		updateBlocks(parse(allBlocks.normalize()));
	}, [] );

	return (
		<div className="playground">
			<SlotFillProvider>
				<BlockEditorProvider
					value={ blocks }
					onInput={ updateBlocks }
					onChange={ updateBlocks }
				>
					<div className="playground_header">
						<Header blocks={blocks} />
					</div>
					<div className="playground__sidebar">
						<BlockInspector />
					</div>
					<div className="playground__content">
						<BlockTools>
							<div className="editor-styles-wrapper">
								<BlockEditorKeyboardShortcuts.Register />
								<WritingFlow>
									<ObserveTyping>
										<BlockList />
									</ObserveTyping>
								</WritingFlow>
							</div>
						</BlockTools>
					</div>
					<Notices />
					<Popover.Slot />
				</BlockEditorProvider>
			</SlotFillProvider>
		</div>
	);
}

export default StandaloneEditor;
