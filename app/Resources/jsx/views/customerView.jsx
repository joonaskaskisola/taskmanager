import React from 'react';
import { render } from 'react-dom';
import CustomerRow from './customerRow.jsx';
import TextField from './fields/textField.jsx';

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
                    <button className="mdl-button mdl-js-button mdl-js-ripple-effect btn-submit-form" onClick={this.props.closeRow}>
                        Close <i className="material-icons">clear</i>
                    </button>

                    <div className="mdl-card__title">
                        <h2 className="mdl-card__title-text">Edit customer details</h2>
                    </div>

                    <div className="mdl-card__supporting-text">
                        <div className="row">
                            <TextField name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                            <TextField name="name2" label="Name 2" value={this.props.row.name2} handleChange={this.props.handleChange} />
                        </div>

                        <div className="row">
                            <TextField name="businessId" label="BusinessId" value={this.props.row.businessId} handleChange={this.props.handleChange} />
                            <TextField name="contactPerson" label="Contact person" value={this.props.row.contactPerson} handleChange={this.props.handleChange} />
                        </div>

                        <h2 className="mdl-card__title-text">Contact details</h2>

                        <div className="row">
                            <TextField name="streetAddress" label="Street address" value={this.props.row.streetAddress} handleChange={this.props.handleChange} />
                            <TextField name="country" label="Country" value={this.props.row.country} handleChange={this.props.handleChange} />
                        </div>

                        <div className="row">
                            <TextField name="zipCode" label="Zipcode" value={this.props.row.zipCode} handleChange={this.props.handleChange} />
                            <TextField name="locality" label="Locality" value={this.props.row.locality} handleChange={this.props.handleChange} />
                        </div>
                    </div>

                    <div className="mdl-card__actions mdl-card--border">
                        <a className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" onClick={this.props.handleSubmit}>
                            Save <i className="material-icons">done</i>
                        </a>
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [];

            this.props.data.forEach(function(customer) {
                rows.push(<CustomerRow customer={customer} key={customer.id} viewRow={self.props.viewRow} />);
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
