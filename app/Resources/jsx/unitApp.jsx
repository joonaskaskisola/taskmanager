import React from 'react';
import BaseApp from './components/base-app.jsx';
import UnitView from './views/unit-view.jsx';
import {NotificationContainer, NotificationManager} from 'react-notifications';
import request from 'superagent';

export default class UnitApp extends BaseApp {
	constructor(props, context) {
		super(props, context);

		this.state.app = 'unit';

		this.handleSubmit = this.handleSubmit.bind(this);
	}

	handleSubmit(event) {
		event.preventDefault();

		request
			.put(BaseApp.getApplicationDataUrl(this.state.app))
			.send(this.state.row)
			.end(function (err, res) {
				if (!err) {
					NotificationManager.success('Row updated!', 'Success');
					return true;
				}

				self.setState({'errors': res.body.error_fields});
				NotificationManager.error('An error occurred', 'Problems detected');
			});
	}

	render() {
		return <div>
			<NotificationContainer/>

			<UnitView
				showEditor={this.props.route.showEditor}
				e={this.state.errors}
				createNew={this.createNew}
				showNext={this.state.next}
				showPrev={this.state.prev}
				nextRow={this.nextRow}
				previousRow={this.previousRow}
				handleSubmit={this.handleSubmit}
				handleChange={this.handleChange}
				closeRow={this.closeRow}
				viewRow={this.viewRow}
				loading={this.state.isLoading}
				row={this.state.row}
				data={this.state.data}/>
		</div>
	}
}
