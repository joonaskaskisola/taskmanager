import React from 'react';
import {Button, Icon} from 'semantic-ui-react'
import {Link} from 'react-router';

export default class NavigationButton extends React.Component {
	render() {
		if (!this.props.show) {
			return <div/>
		}

		if (this.props.icon === 'remove') {
			return <Link to={'/' + this.props.app}>
				<Button
					content={this.props.leftLabel + this.props.rightLabel}
					labelPosition={this.props.rightLabel === '' ? 'right' : 'left'}
					icon={this.props.icon}
					primary={this.props.primary || false}
					onClick={this.props.onClick}/>
			</Link>
		}

		return <Button
			content={this.props.leftLabel + this.props.rightLabel}
			labelPosition={this.props.rightLabel === '' ? 'right' : 'left'}
			icon={this.props.icon}
			primary={this.props.primary || false}
			onClick={this.props.onClick}/>
	}
}
