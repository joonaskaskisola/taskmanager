import React from 'react';
import { render } from 'react-dom';

export default class NumericField extends React.Component {
    render() {
        let errorClass = "", fieldName = this.props.name;

        if (typeof this.props.e !== "undefined") {
            if (fieldName in this.props.e) {
                errorClass = "error";
            }
        }

        return <div className={"field " + errorClass} style={{"float": this.props.pos}}>
            <input
                pattern={this.props.pattern ? this.props.pattern : "[0-9]+"}
                value={this.props.value ? this.props.value : ''}
                className="mdl-textfield__input"
                type="text" id={this.props.name} name={this.props.name}
                onChange={this.props.handleChange}/>

            <label htmlFor={this.props.name}>{this.props.label}:</label>
        </div>
    }
}
