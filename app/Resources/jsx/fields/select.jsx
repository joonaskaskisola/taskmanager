import React from 'react';
import {render} from 'react-dom';
import { Dropdown } from 'semantic-ui-react'

export default class SelectField extends React.Component {
    render() {
        return <div>
            <Dropdown placeholder={this.props.label} fluid selection options={this.props.options} />
        </div>
    }
}
