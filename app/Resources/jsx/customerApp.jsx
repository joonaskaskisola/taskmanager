import React from 'react';
import {render} from 'react-dom';
import BaseApp from './components/base-app.jsx';
import CustomerView from './views/customer-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';

export default class CustomerApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.app = "customer";
        this.state.loadExtraInfo = true;
        this.state.countries = [];

        let self = this;

        this.getData("/api/country", function (err, data) {
            data.forEach(function(country) {
                self.state.countries.push({'value': country.name, 'text': country.name, 'flag': country.code.toLowerCase()});
            })
        });

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        if (this.state.row['id'] !== undefined) {
            axios.put(BaseApp.getApplicationDataUrl(this.state.app), this.state.row).then(function (response) {
                NotificationManager.success("Row updated!", "Success");
            }).catch(function (error) {
                NotificationManager.error(error.toString(), "Problems detected");
            });
        } else {
            axios.post(BaseApp.getApplicationDataUrl(this.state.app), this.state.row).then(function (response) {
                NotificationManager.success("Row updated!", "Success");
            }).catch(function (error) {
                NotificationManager.error(error.toString(), "Problems detected");
            });
        }
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div className="ui segment">
                <div className={"ui inverted  " + (this.state.isLoading ? "active" : "") + " dimmer"}>
                    <div className="ui loader"></div>
                </div>

                <CustomerView
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
