import React from 'react';
import { Checkbox } from 'semantic-ui-react'

export default class CheckboxField extends React.Component {
    render() {
        return <div className="field">
            <Checkbox name={this.props.name} label={this.props.label} onChange={this.props.handleChange} />
        </div>
    }
}
