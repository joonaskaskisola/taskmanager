import React from 'react';
import { render } from 'react-dom';
import { Button, Icon } from 'semantic-ui-react'

export default class NavigationButton extends React.Component {
    render() {
        if (!this.props.show) {
            return <div/>
        }

        return <Button onClick={this.props.onClick}>
            {this.props.leftLabel}
            <Icon name={this.props.icon}/>
            {this.props.rightLabel}
        </Button>
    }
}
