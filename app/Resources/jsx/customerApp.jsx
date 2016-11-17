import React from 'react';
import { render } from 'react-dom';
import AppComponent from './components/appComponent.jsx';
import CustomerView from './views/customerView.jsx';
import { NotificationContainer, NotificationManager } from 'react-notifications';

export default class CustomerApp extends AppComponent {
    constructor(props, context) {
        super(props, context);

        this.state.dataUrl = "/api/customer";

        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(event) {
        event.preventDefault();

        let self = this;
        this.setState({"isLoading": true});

        axios.put(this.state.dataUrl, this.state.row).then(function (response) {
            self.setState({"isLoading": false});

            NotificationManager.success("Success!");
        }).catch(function (error) {
            self.setState({"isLoading": false});

            NotificationManager.error(error.toString(), "Virhe havaittu");
        });
    }

    render() {
        return <div>
            <NotificationContainer/>

            <div
                style={{display: this.state.isLoading ? "block" : "none"}}
                className="loading-bar mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>

            <CustomerView
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
