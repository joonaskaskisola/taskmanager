import React from 'react';

export default class TextAreaField extends React.Component {
    render() {
        return <div className={"field" + (this.props.e[this.props.name] ? "error": "")}>
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
