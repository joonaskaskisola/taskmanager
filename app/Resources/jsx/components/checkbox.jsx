import React from 'react';
import { render } from 'react-dom';

export default class CheckboxField extends React.Component {
    render() {
        return <div className="field">
            <div className="ui checkbox">
                <input type="checkbox" tabIndex="0" className="hidden" />
                <label>{this.props.label}</label>
            </div>
        </div>
    }
}
