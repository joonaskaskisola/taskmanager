import React from 'react';
import { render } from 'react-dom';

export default class TextField extends React.Component {
    render() {
        return <div className={this.props.width + "wide field"}>
            <label htmlFor={this.props.name}>{this.props.label}:</label>

            <input
                value={this.props.value ? this.props.value : ''}
                type="text"
                id={this.props.name}
                name={this.props.name}
                onChange={this.props.handleChange}/>
        </div>
    }
}
