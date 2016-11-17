import React from 'react';
import { render } from 'react-dom';
import CustomerTableRow from '../rows/customer-table-row.jsx';
import TextField from '../fields/text-field.jsx';
import NavigationButton from '../components/navigation-button.jsx';

export default class CustomerView extends React.Component {
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
                <div className="form-card-wide mdl-data-table mdl-card mdl-shadow--2dp">
                    <div className="mdl-card__title card-navigation">
                        <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="keyboard_arrow_right" />
                        <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="keyboard_arrow_left" />

                        <h1 style={{width: "125px", margin: "0 auto", textAlign: "center"}} className="mdl-card__title-text">Customer</h1>
                    </div>

                    <div className="form-separator">
                        <h2 className="mdl-card__title-text">General info</h2>
                    </div>

                    <div className="mdl-card__supporting-text">
                        <div className="row">
                            <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                            <TextField pos="right" name="name2" label="Name 2" value={this.props.row.name2} handleChange={this.props.handleChange} />
                        </div>

                        <div className="row">
                            <TextField pos="left" name="businessId" label="BusinessId" value={this.props.row.businessId} handleChange={this.props.handleChange} />
                            <TextField pos="right" name="contactPerson" label="Contact person" value={this.props.row.contactPerson} handleChange={this.props.handleChange} />
                        </div>
                    </div>

                    <div className="form-separator">
                        <h2 className="mdl-card__title-text">Contact details</h2>
                    </div>

                    <div className="mdl-card__supporting-text">
                        <div className="row">
                            <TextField pos="left" name="streetAddress" label="Street address" value={this.props.row.streetAddress} handleChange={this.props.handleChange} />
                            <TextField pos="right" name="country" label="Country" value={this.props.row.country} handleChange={this.props.handleChange} />
                        </div>

                        <div className="row">
                            <TextField pos="left" name="zipCode" label="Zipcode" value={this.props.row.zipCode} handleChange={this.props.handleChange} />
                            <TextField pos="right" name="locality" label="Locality" value={this.props.row.locality} handleChange={this.props.handleChange} />
                        </div>
                    </div>

                    <div className="mdl-card__actions mdl-card--border">
                        <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="clear" />
                        <NavigationButton show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" rightLabel="" icon="done" />
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [];

            this.props.data.forEach(function(customer) {
                rows.push(<CustomerTableRow customer={customer} key={customer.id} viewRow={self.props.viewRow} />);
            });

            return <div>
                <table className="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                    <thead>
                    <tr>
                        <th className="mdl-data-table__cell--non-numeric">name</th>
                        <th className="mdl-data-table__cell--non-numeric">businessId</th>
                        <th className="mdl-data-table__cell--non-numeric">streetAddress</th>
                        <th className="mdl-data-table__cell--non-numeric">country</th>
                    </tr>
                    </thead>

                    <tbody>{rows}</tbody>
                </table>
            </div>
        }
    }
}
