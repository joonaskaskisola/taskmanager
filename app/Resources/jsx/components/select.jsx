import React from 'react';
import {Dropdown} from 'semantic-ui-react'

export default class SelectField extends React.Component {
	render() {
		let errorClass = '', fieldName = this.props.name;

		if (typeof this.props.e !== 'undefined') {
			if (fieldName in this.props.e) {
				errorClass = 'error';
			}
		}

		return <div className={'field ' + errorClass}>
			<label htmlFor={this.props.name}>{this.props.label}:</label>

			<Dropdown
				search={!!this.props.search}
				value={this.props.value}
				onChange={this.props.handleChange}
				name={this.props.name}
				placeholder={this.props.label}
				fluid
				selection
				options={this.props.options}/>
		</div>
	}
}
