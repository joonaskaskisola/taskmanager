import React from 'react';
import { render } from 'react-dom';
import { Route, ReactRouter, Router, browserHistory } from 'react-router';
import GridApp from './components/gridApp.jsx';

export default class CustomerApp extends GridApp {
    constructor(props, context) {
        super(props, context, "/api/customer");
    }

    renderRow() {
        return <div>
            <div className="form-card-wide mdl-data-table mdl-card mdl-shadow--2dp">
                <div className="mdl-card__title">
                    <h2 className="mdl-card__title-text">Edit customer details</h2>
                </div>

                <div className="mdl-card__supporting-text">
                    <div className="row">
                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Name: <input
                                    value={this.state.row.name}
                                    className="mdl-textfield__input"
                                    type="text" id="name" name="name"
                                    onChange={this.handleChange}/>
                            </div>
                        </div>

                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Name2: <input
                                    value={this.state.row.name2}
                                    className="mdl-textfield__input"
                                    type="text" id="name2" name="name2"
                                    onChange={this.handleChange}/>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Business-id: <input
                                value={this.state.row.businessId}
                                className="mdl-textfield__input"
                                type="text" id="businessId" name="businessId"
                                onChange={this.handleChange}/>
                            </div>
                        </div>

                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Contact person: <input
                                value={this.state.row.contactPerson}
                                className="mdl-textfield__input"
                                type="text" id="contactPerson" name="contactPerson"
                                onChange={this.handleChange}/>
                            </div>
                        </div>
                    </div>

                    <h2 className="mdl-card__title-text">Contact details</h2>

                    <div className="row">
                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Street address: <input
                                value={this.state.row.streetAddress}
                                className="mdl-textfield__input"
                                type="text" id="streetAddress" name="streetAddress"
                                onChange={this.handleChange}/>
                            </div>
                        </div>

                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Country: <input
                                value={this.state.row.zipCode}
                                className="mdl-textfield__input"
                                type="text" id="country" name="country"
                                onChange={this.handleChange}/>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Zipcode: <input
                                value={this.state.row.zipCode}
                                className="mdl-textfield__input"
                                type="text" id="zipCode" name="zipCode"
                                onChange={this.handleChange}/>
                            </div>
                        </div>

                        <div className="row-column">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                Locality: <input
                                value={this.state.row.locality}
                                className="mdl-textfield__input"
                                type="text" id="locality" name="locality"
                                onChange={this.handleChange}/>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mdl-card__actions mdl-card--border">
                    <a className="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                        Save <i className="material-icons">done</i>
                    </a>

                    <button className="mdl-button mdl-js-button mdl-js-ripple-effect btn-submit-form" onClick={this.closeRow}>
                        Close <i className="material-icons">clear</i>
                    </button>
                </div>
            </div>
        </div>
    }

    renderRows() {
        let self = this;

        return <table className="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
            <thead>
                <tr>
                    <th className="mdl-data-table__cell--non-numeric">name</th>
                    <th className="mdl-data-table__cell--non-numeric">businessId</th>
                    <th className="mdl-data-table__cell--non-numeric">streetAddress</th>
                    <th className="mdl-data-table__cell--non-numeric">country</th>
                </tr>
            </thead>

            <tbody>
            {this.state.data.map(function (listValue) {
                return <tr key={listValue.id} onClick={() => { self.viewRow(listValue.id) }} className="pointertr">
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.name }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.businessId}</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.streetAddress }</td>
                    <td className="mdl-data-table__cell--non-numeric">{ listValue.country }</td>
                </tr>
            })}
            </tbody>
        </table>
    }

    render() {
        return <div>
            <div
                style={{display: this.state.isLoading ? "block" : "none"}}
                className="loading-bar mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>

            {!this.state.isLoading && this.state.row && this.renderRow() }
            {!this.state.isLoading && !this.state.row && this.renderRows() }
        </div>
    }
}

render(
    <CustomerApp/>,
    document.getElementById('customerApp')
);
