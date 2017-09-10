import React from 'react';

export default class TextField extends React.Component {
	render() {
		let errorClass = '', fieldName = this.props.name;

		if (typeof this.props.e !== 'undefined') {
			if (fieldName in this.props.e) {
				errorClass = 'error';
			}
		}

		return <div className={'field ' + errorClass}>
			<label htmlFor={this.props.name}>{this.props.label}:</label>

			<input
				readOnly={!!this.props.readOnly}
				value={this.props.value ? this.props.value : ''}
				type='text'
				id={this.props.name}
				name={this.props.name}
				onChange={this.props.handleChange}/>
		</div>
	}
}
