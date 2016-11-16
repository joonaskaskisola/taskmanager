import React from 'react';
import { render } from 'react-dom';

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
                    <div className="mdl-card__title">
                        <h2 className="mdl-card__title-text">Edit customer details</h2>
                    </div>

                    <div className="mdl-card__supporting-text">
                        <div className="row">
                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Name: <input
                                    value={this.props.row.name}
                                    className="mdl-textfield__input"
                                    type="text" id="name" name="name"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>

                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Name2: <input
                                    value={this.props.row.name2}
                                    className="mdl-textfield__input"
                                    type="text" id="name2" name="name2"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Business-id: <input
                                    value={this.props.row.businessId}
                                    className="mdl-textfield__input"
                                    type="text" id="businessId" name="businessId"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>

                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Contact person: <input
                                    value={this.props.row.contactPerson}
                                    className="mdl-textfield__input"
                                    type="text" id="contactPerson" name="contactPerson"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>
                        </div>

                        <h2 className="mdl-card__title-text">Contact details</h2>

                        <div className="row">
                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Street address: <input
                                    value={this.props.row.streetAddress}
                                    className="mdl-textfield__input"
                                    type="text" id="streetAddress" name="streetAddress"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>

                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Country: <input
                                    value={this.props.row.country}
                                    className="mdl-textfield__input"
                                    type="text" id="country" name="country"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Zipcode: <input
                                    value={this.props.row.zipCode}
                                    className="mdl-textfield__input"
                                    type="text" id="zipCode" name="zipCode"
                                    onChange={this.props.handleChange}/>
                                </div>
                            </div>

                            <div className="row-column">
                                <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    Locality: <input
                                    value={this.props.row.locality}
                                    className="mdl-textfield__input"
                                    type="text" id="locality" name="locality"
                                    onChange={this.props.handleChange}/>
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
        } else if (this.props.data.length > 0) {
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

                    <tbody>
                    {this.props.data.map(function (listValue) {
                        return <tr key={listValue.id} onClick={() => { self.props.viewRow(listValue.id) }} className="pointertr">
                            <td className="mdl-data-table__cell--non-numeric">{ listValue.name }</td>
                            <td className="mdl-data-table__cell--non-numeric">{ listValue.businessId}</td>
                            <td className="mdl-data-table__cell--non-numeric">{ listValue.streetAddress }</td>
                            <td className="mdl-data-table__cell--non-numeric">{ listValue.country }</td>
                        </tr>
                    })}
                    </tbody>
                </table>
            </div>
        }
    }
}
