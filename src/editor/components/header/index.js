/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { serialize } from '@wordpress/blocks';
import { useDispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';


function Header({blocks}) {
	const { createInfoNotice }       = useDispatch('core/notices');
	const [isSaving, setSavingState] = useState('no');

	const SaveData = () => {
		const allBlocksData = serialize(blocks);
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: sg.ajax_url,
			data: {
				action: "save_block_data",
				data: allBlocksData,
			},
			success: function (response) {
				createInfoNotice('Blocks Saved', {
					type: 'snackbar',
					isDismissible: true,
				});
				setSavingState('saved');
				setTimeout(() => {
					setSavingState('no');
				}, 1000)
			},
			error: function (jqXHR, exception) {
				createInfoNotice(`Error : ${jqXHR.statusText}`, {
					type: 'snackbar',
					isDismissible: true,
				});
				console.error("Error :", jqXHR.statusText)
				setSavingState('no');
			}
		})
	}
	return (
		<div className="interface-interface-skeleton__header"
			role="region"
			aria-label={__('Editor top bar', 'bwfapemail')}
			tabIndex="-1"
		>

			<div className="edit-post-header" role="region" tabIndex="-1">
				<div className="edit-post-header__settings" style={{padding:'10px', float:'right'}}>
					<Button
						isPrimary
						onClick={() => {
							setSavingState('saving')
							SaveData()
						}}
						disabled={isSaving !== 'no' ? true : false}>
							{isSaving === 'no' ? 'Save' : (isSaving != 'saved' ? 'Saving' : 'Saved')}
					</Button>
				</div>
			</div>
		</div >
	);
}

export default Header;