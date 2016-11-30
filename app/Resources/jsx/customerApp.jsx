import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import CustomerView from './views/customer-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import request from 'superagent';

export default class CustomerApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "customer";

        let self = this;

        this.state.countries = [];
        this.getData("/api/country", function (err, data) {
            data.forEach(function(country) {
                self.state.countries.push({'value': country.name, 'text': country.name, 'flag': country.code.toLowerCase()});
            });
        });

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        let self = this;

        request
            .put(BaseApp.getApplicationDataUrl(this.state.app))
            .send(this.state.row)
            .end(function (err, res) {
                if (!err) {
                    NotificationManager.success("Row updated!", "Success");
                    return true;
                }

                self.setState({"errors": res.body.error_fields});
                NotificationManager.error("An error occured", "Problems detected");
            });
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <CustomerView
                    e={this.state.errors}
                    createNew={this.createNew}
                    countries={this.state.countries}
                    showNext={this.state.next}
                    showPrev={this.state.prev}
                    nextRow={this.nextRow}
                    previousRow={this.previousRow}
                    handleSubmit={this.handleSubmit}
                    handleSelectChange={this.handleSelectChange}
                    handleChange={this.handleChange}
                    closeRow={this.closeRow}
                    viewRow={this.viewRow}
                    loading={this.state.isLoading}
                    row={this.state.row}
                    data={this.state.data}/>
            </div>
        </div>
    }
}

render(
    <CustomerApp/>,
    document.getElementById('customerApp')
);
