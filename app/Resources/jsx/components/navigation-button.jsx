import React from 'react';
import { Button, Icon } from 'semantic-ui-react'

export default class NavigationButton extends React.Component {
    render() {
        if (!this.props.show) {
            return <div/>
        }

        return <Button primary={this.props.primary || false} onClick={this.props.onClick}>
            {this.props.leftLabel}
            <Icon name={this.props.icon}/>
            {this.props.rightLabel}
        </Button>
    }
}
