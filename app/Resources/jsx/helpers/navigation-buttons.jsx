import React from 'react';
import { render } from 'react-dom';
import NavigationButton from '../components/navigation-button.jsx';

export default class NavigationButtons extends React.Component {
    render() {
        if (this.props.header) {
            return <div style={{overflow: "hidden"}}>
                <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="double right angle icon"/>
                <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="double left angle icon"/>
            </div>
        } else if (this.props.footer) {
            return <div className="navigation-footer-buttons">
                {this.props.closeRow && <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="clear" />}
                {this.props.handleSubmit && <NavigationButton primary={true} show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" />}
            </div>
        }
    }
}
