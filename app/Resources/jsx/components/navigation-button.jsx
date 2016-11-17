import React from 'react';
import { render } from 'react-dom';

export default class NavigationButton extends React.Component {
    render() {
        return <div>
            {this.props.show && <button onClick={this.props.onClick} style={{float: this.props.float}} className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                {this.props.leftLabel} <i className="material-icons">{this.props.icon}</i> {this.props.rightLabel}
            </button>}
        </div>
    }
}
