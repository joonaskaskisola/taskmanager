import React from 'react';
import {render} from 'react-dom';

export default class NavigationButton extends React.Component {
    render() {
        return <div className={this.props.float}>
            {this.props.show && <div onClick={this.props.onClick} style={{float: this.props.float}} className={"ui " + this.props.float + " labeled icon button"} tabIndex="0">
                {this.props.leftLabel} <i className={this.props.icon + " icon"}></i> {this.props.rightLabel}
            </div>}
        </div>
    }
}
