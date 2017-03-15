import React from 'react';
import { Button, Icon } from 'semantic-ui-react'
import { Link } from 'react-router';

export default class NavigationButton extends React.Component {
    render() {
        if (!this.props.show) {
            return <div/>
        }

        if (this.props.icon === 'remove') {
            return <Link to={"/" + this.props.app}>
                <Button primary={this.props.primary || false} onClick={this.props.onClick}>
                    {this.props.leftLabel}
                    <Icon name={this.props.icon}/>
                    {this.props.rightLabel}
                </Button>
            </Link>
        }

        return <Button primary={this.props.primary || false} onClick={this.props.onClick}>
            {this.props.leftLabel}
            <Icon name={this.props.icon}/>
            {this.props.rightLabel}
        </Button>
    }
}
