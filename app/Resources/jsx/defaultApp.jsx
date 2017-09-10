import React from 'react';
import BaseApp from './components/base-app.jsx';
import {NotificationContainer} from 'react-notifications';
import {List, Segment, Button, Divider} from 'semantic-ui-react'
import {Link} from 'react-router';

export default class DefaultApp extends BaseApp {
	constructor(props, context) {
		super(props, context);

		this.state = {
			app: 'default',
			events: []
		};

		let self = this;
		this.getData('/api/events', function (err, eventData) {
			self.setState({'events': eventData});
		});
	}

	render() {
		let events = [];

		this.state.events.forEach(function (event, i) {
			events.push(<List.Item name={i} key={'event-' + i}>
					<List.Icon name='heartbeat' size='large' verticalAlign='middle'/>
					<List.Content>
						<List.Header as='a'>{ event.name } ( { event.data.user.username }
							): { event.data.name }</List.Header>
						<List.Description as='a'>
							{ event.datetime }
							&nbsp; <List.Icon name='hashtag' size='small' verticalAlign='middle'/>{ event.data.id }
						</List.Description>
					</List.Content>
				</List.Item>
			);
		});

		return <div className='ui segment'>

			<NotificationContainer/>
			<Link to={'/tasks'} className='item'>
				<Button primary fluid>View tasks</Button>
			</Link>

			<Divider horizontal>Latest changes</Divider>

			<List divided relaxed>
				{ events }
			</List>
		</div>
	}
}
