import React from 'react';
import { render } from 'react-dom';
import BaseApp from './components/base-app.jsx';
import CustomerView from './views/customer-view.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';

export default class CustomerApp extends BaseApp {
    constructor(props, context) {
        super(props, context);

        this.state.dataUrl = "/api/customer";

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        axios.put(this.state.dataUrl, this.state.row).then(function (response) {
            NotificationManager.success("Row updated!", "Success");
        }).catch(function (error) {
            NotificationManager.error(error.toString(), "Problems detected");
        });
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div
                style={{display: this.state.isLoading ? "block" : "none"}}
                className="loading-bar mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>

            <CustomerView
                showNext={this.state.next}
                showPrev={this.state.prev}
                nextRow={this.nextRow}
                previousRow={this.previousRow}
                handleSubmit={this.handleSubmit}
                handleChange={this.handleChange}
                closeRow={this.closeRow}
                viewRow={this.viewRow}
                loading={this.state.isLoading}
                row={this.state.row}
                data={this.state.data}/>
        </div>
    }
}

render(
    <CustomerApp/>,
    document.getElementById('customerApp')
);
