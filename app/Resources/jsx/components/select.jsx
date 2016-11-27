import React from 'react';
import { render } from 'react-dom';
import { Dropdown } from 'semantic-ui-react'

export default class SelectField extends React.Component {
    render() {
        return <div className={"field"}>
            <label htmlFor={this.props.name}>{this.props.label}:</label>

            <Dropdown
                search={!!this.props.search}
                value={this.props.value}
                onChange={this.props.handleChange}
                name={this.props.name}
                placeholder={this.props.label}
                fluid
                selection
                options={this.props.options} />
        </div>
    }
}
