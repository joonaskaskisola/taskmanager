import React from 'react';
import { render } from 'react-dom';

export default class TextField extends React.Component {
    render() {
        return <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style={{"float": this.props.pos}}>
            <input
            value={this.props.value ? this.props.value : ''}
            className="mdl-textfield__input"
            type="text" id={this.props.name} name={this.props.name}
            onChange={this.props.handleChange}/>

            <label htmlFor={this.props.name}>{this.props.label}:</label>
        </div>
    }
}
