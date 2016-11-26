import React from 'react';
import { render } from 'react-dom';

export default class TextAreaField extends React.Component {
    render() {
        return <div className={this.props.width + "wide field"}>
            <label htmlFor={this.props.name}>{this.props.label}:</label>

            <textarea
                readOnly={!!this.props.readOnly}
                defaultValue={this.props.value ? this.props.value : ''}
                id={this.props.name}
                name={this.props.name}
                onChange={this.props.handleChange}/>
        </div>
    }
}
