import React from 'react';
import { render } from 'react-dom';
import { Route, ReactRouter, Router, browserHistory } from 'react-router';
import GridApp from './components/gridApp.jsx';
import CustomerView from './views/customerView.jsx';

export default class CustomerApp extends GridApp {
    constructor(props, context) {
        super(props, context, "/api/customer");
    }

    render() {
        return <div>
            <div
                style={{display: this.state.isLoading ? "block" : "none"}}
                className="loading-bar mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>

            <CustomerView
                handleChange={this.handleChange}
                viewRow={this.viewRow}
                loading={this.state.isLoading}
                row={this.state.row}
                data={this.state.data} />
        </div>
    }
}

render(
    <CustomerApp/>,
    document.getElementById('customerApp')
);
