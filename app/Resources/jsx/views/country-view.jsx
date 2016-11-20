import React from 'react';
import { render } from 'react-dom';
import Row from '../rows/row.jsx';
import TextField from '../fields/text.jsx';
import NavigationButton from '../components/navigation-button.jsx';

export default class CountryView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        let self = this;

        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <div className="">
                        <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="angle double left icon" />
                        <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="angle double right icon" />

                        <h1 style={{width: "125px", margin: "0 auto", textAlign: "center"}}>Country</h1>
                    </div>

                    <h4 className="ui dividing header">General Information</h4>

                    <div className="two fields">
                        <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                        <TextField pos="right" name="code" label="Code" value={this.props.row.code} handleChange={this.props.handleChange} />
                    </div>

                    <div className="navigation-footer-buttons">
                        <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="clear" />
                        <NavigationButton show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" rightLabel="" icon="done" />
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [];

            this.props.data.forEach(function(row) {
                rows.push(<Row fields={['name', 'code']} row={row} key={row.id} viewRow={self.props.viewRow} />);
            });

            return <div className="ui computer equal width grid">
                <div className="row blue">
                    <div className="column">name</div>
                </div>

                {rows}
            </div>
        }
    }
}
