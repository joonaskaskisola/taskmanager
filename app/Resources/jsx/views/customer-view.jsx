import React from 'react';
import { render } from 'react-dom';
import CustomerRow from '../rows/customer-row.jsx';
import TextField from '../fields/text.jsx';
import NavigationButton from '../components/navigation-button.jsx';
import SelectField from '../fields/select.jsx';

export default class CustomerView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        let self = this;

        if (this.props.loading && !this.props.row) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <div className="">
                        <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="double right angle icon" />
                        <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="double left angle icon" />

                        <h1 style={{width: "125px", margin: "0 auto", textAlign: "center"}} className="mdl-card__title-text">Customer</h1>
                    </div>

                    <h4 className="ui dividing header">Shipping Information</h4>

                    <div className="two fields">
                        <TextField width="six" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                        <TextField width="six" name="name2" label="Name 2" value={this.props.row.name2} handleChange={this.props.handleChange} />
                    </div>

                    <div className="two fields">
                        <TextField width="six" name="businessId" label="BusinessId" value={this.props.row.businessId} handleChange={this.props.handleChange} />
                        <TextField width="six" name="contactPerson" label="Contact person" value={this.props.row.contactPerson} handleChange={this.props.handleChange} />
                    </div>

                    <h4 className="ui dividing header">Customer information</h4>

                    <div className="two fields">
                        <TextField width="six" name="streetAddress" label="Street address" value={this.props.row.streetAddress} handleChange={this.props.handleChange} />
                        <SelectField width="six" name="country" label="Country" options={[
                            {value: "1", text: "1"},
                            {value: "2", text: "2"},
                            {value: "3", text: "3"},
                        ]} value={this.props.row.country} />
                    </div>

                    <div className="two fields">
                        <TextField width="six" name="zipCode" label="Zipcode" value={this.props.row.zipCode} handleChange={this.props.handleChange} />
                        <TextField width="six" name="locality" label="Locality" value={this.props.row.locality} handleChange={this.props.handleChange} />
                    </div>

                    <div className="">
                        <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" />
                        <NavigationButton show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" />
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [];

            this.props.data.forEach(function(customer) {
                rows.push(<CustomerRow customer={customer} key={customer.id} viewRow={self.props.viewRow} />);
            });

            return <div className="ui computer equal width grid">
                <div className="row blue">
                    <div className="column">name</div>
                    <div className="column">businessId</div>
                    <div className="column">streetAddress</div>
                    <div className="column">country</div>
                </div>

                {rows}
            </div>
        }
    }
}
