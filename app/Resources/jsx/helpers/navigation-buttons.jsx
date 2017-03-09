import React from 'react';
import NavigationButton from '../components/navigation-button.jsx';
import { Menu } from 'semantic-ui-react'

export default class NavigationButtons extends React.Component {
    render() {
        if (this.props.header) {
            return <Menu.Menu position="right">
                <Menu.Item>
                    <NavigationButton show={this.props.showPrev} onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="double left angle"/>
                </Menu.Item>

                <Menu.Item>
                    <NavigationButton show={this.props.showNext} onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="double right angle"/>
                </Menu.Item>
            </Menu.Menu>
        } else if (this.props.footer) {
            return <Menu.Menu position="right">
                <Menu.Item>
                    {this.props.closeRow && <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="remove" />}
                </Menu.Item>

                <Menu.Item>
                    {this.props.handleSubmit && <NavigationButton primary={true} show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" />}
                </Menu.Item>
            </Menu.Menu>
        }
    }
}
