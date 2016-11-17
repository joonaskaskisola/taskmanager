import React from 'react';
import { render } from 'react-dom';

export default class TextField extends React.Component {
    render() {
        return <div className="row-column">
            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                {this.props.label}: <input
                value={this.props.value ? this.props.value : ''}
                className="mdl-textfield__input"
                type="text" id={this.props.name} name={this.props.name}
                onChange={this.props.handleChange}/>
            </div>
        </div>
    }
}
